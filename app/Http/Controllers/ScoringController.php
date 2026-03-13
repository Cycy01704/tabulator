<?php

namespace App\Http\Controllers;

use App\Models\Contestant;
use App\Models\Criterion;
use App\Models\Score;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScoringController extends Controller
{
    /**
     * Display a listing of contestants to be scored.
     */
    public function index()
    {
        $judge = auth()->user();
        $activeEvent = \App\Models\Event::current();
        
        $contestants = $activeEvent ? $activeEvent->contestants->sortBy('number') : collect([]);
        
        return view('scoring.index', compact('contestants', 'judge', 'activeEvent'));
    }

    /**
     * Show the scoring form for a specific contestant.
     */
    public function create(Contestant $contestant)
    {
        $judge = auth()->user();
        $activeEvent = \App\Models\Event::current();

        if (!$activeEvent || $contestant->event_id !== $activeEvent->id) {
            return redirect()->route('scoring.index')->with('error', 'Contestant not found in the current active event.');
        }

        $criteria = $activeEvent->criteria()->with('grades')->get();
        
        // Get existing scores for this judge and contestant
        $existingScores = Score::where('judge_id', $judge->id)
            ->where('contestant_id', $contestant->id)
            ->get()
            ->pluck('score', 'criterion_id');

        return view('scoring.create', compact('contestant', 'judge', 'criteria', 'existingScores', 'activeEvent'));
    }

    /**
     * Store or update scores for a contestant.
     * Includes tie-prevention: rejects if proposed total matches another contestant's total for this judge.
     */
    public function store(Request $request, Contestant $contestant)
    {
        $activeEvent = \App\Models\Event::current();
        if (!$activeEvent || $contestant->event_id !== $activeEvent->id) {
            return redirect()->route('scoring.index')->with('error', 'Invalid contestant or event.');
        }

        $request->validate([
            'scores' => 'required|array',
            'scores.*' => 'required|numeric|min:0',
        ]);

        $judgeId = auth()->id();
        $proposedTotal = array_sum($request->scores);

        // Security: Validate that all criteria belong to the active event
        $validCriteriaIds = $activeEvent->criteria()->pluck('id')->toArray();
        foreach (array_keys($request->scores) as $submittedId) {
            if (!in_array($submittedId, $validCriteriaIds)) {
                return back()->with('error', 'Invalid criteria submitted.');
            }
        }

        // Get all other contestants this judge has scored in this event
        $otherContestantIds = Score::where('judge_id', $judgeId)
            ->where('contestant_id', '!=', $contestant->id)
            ->whereIn('contestant_id', $activeEvent->contestants()->pluck('id'))
            ->pluck('contestant_id')
            ->unique();

        // Check each other contestant's total for a tie
        foreach ($otherContestantIds as $otherContestantId) {
            $otherTotal = Score::where('judge_id', $judgeId)
                ->where('contestant_id', $otherContestantId)
                ->sum('score');

            if (round($proposedTotal, 2) == round($otherTotal, 2)) {
                $otherContestant = Contestant::find($otherContestantId);
                $name = $otherContestant ? $otherContestant->name : "Contestant #{$otherContestantId}";

                return back()
                    ->withInput()
                    ->with('error', "Tie detected! Your total score ({$proposedTotal}) would match {$name}. Please adjust your grades to avoid a tie.");
            }
        }

        DB::transaction(function () use ($request, $contestant, $judgeId) {
            foreach ($request->scores as $criterionId => $scoreValue) {
                Score::updateOrCreate(
                    [
                        'judge_id' => $judgeId,
                        'contestant_id' => $contestant->id,
                        'criterion_id' => $criterionId,
                    ],
                    ['score' => $scoreValue]
                );
            }
        });

        return redirect()->route('scoring.index')
            ->with('success', 'Scores saved successfully for ' . $contestant->name);
    }
}
