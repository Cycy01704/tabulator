<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\Contestant;
use App\Models\Criterion;
use App\Models\Event;
use App\Models\Grade;
use App\Models\Score;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArchiveController extends Controller
{
    /**
     * Display a listing of the archived events.
     */
    public function index()
    {
        $archives = Archive::orderBy('created_at', 'desc')->get();
        return view('archive.index', compact('archives'));
    }

    /**
     * Store current event data to archive and clear current tables.
     */
    public function store(Request $request)
    {
        $request->validate([
            'passkey' => 'required|string',
        ]);

        $savedPasskey = Setting::getValue('system_passkey', '1234');
        if ($request->passkey !== $savedPasskey) {
            return back()->with('error', 'Invalid administrative passkey. Event conclusion aborted.');
        }

        $currentEvent = Event::current();
        if (!$currentEvent) {
            return back()->with('error', 'No active event found to conclude.');
        }

        $eventName = $currentEvent->name;
        
        // Prepare data for archiving (scoped to current event)
        $data = [
            'event'       => $currentEvent,
            'contestants' => $currentEvent->contestants()->with('scores')->get(),
            'criteria'    => $currentEvent->criteria()->with('grades')->get(),
            'scores'      => Score::whereIn('contestant_id', $currentEvent->contestants()->pluck('id'))->get(),
            'settings'    => Setting::all(),
        ];

        DB::transaction(function () use ($currentEvent, $eventName, $data) {
            // Save to archive
            Archive::create([
                'event_name' => $eventName,
                'event_date' => now(),
                'data'       => $data,
            ]);

            // Mark current event as concluded
            $currentEvent->update([
                'status' => Event::STATUS_CONCLUDED,
                'concluded_at' => now(),
            ]);

            // Reset leaderboard visibility for the next event
            Setting::setValue('leaderboard_visible', '0');

            // We no longer delete/truncate here because contestants/criteria 
            // are formally linked to this concluded event. 
            // The UI filters will now automatically hide them from active management.
        });

        return redirect()->route('events.index')->with('success', 'Event concluded and archived successfully.');
    }

    /**
     * Display the specified archived event.
     */
    public function show($id)
    {
        $archive = Archive::findOrFail($id);
        $data = $archive->data;
        
        $contestants = collect($data['contestants'] ?? []);
        $scores = collect($data['scores'] ?? []);
        $criteria = collect($data['criteria'] ?? []);
        
        $settings = collect($data['settings'] ?? []);
        $formula = $settings->where('key', 'tabulation_formula')->first()['value'] ?? 'normal';
        $totalWeight = $criteria->sum('weight');

        $judgeCount = $scores->pluck('judge_id')->unique()->count();
        $totalPossible = $contestants->count() * $criteria->count();

        $rankings = $contestants->map(function ($contestant) use ($scores, $totalPossible, $judgeCount, $formula, $criteria, $totalWeight) {
            $cScores = $scores->where('contestant_id', $contestant['id']);
            $totalScoreSum = $cScores->sum('score');
            $count = $cScores->count();
            $distinctJudges = $cScores->pluck('judge_id')->unique()->count();
            
            if ($formula === 'weighted' && $totalWeight > 0) {
                // Historically weighted average calculation
                $judgeScores = $cScores->groupBy('judge_id')->map(function ($judgeGroup) use ($criteria, $totalWeight) {
                    $weightedSum = 0;
                    foreach ($judgeGroup as $score) {
                        // Ensure $score is treated as an array from the archived data
                        $scoreArr = (array) $score;
                        $criterionId = $scoreArr['criterion_id'] ?? null;
                        
                        $criterion = $criteria->firstWhere('id', $criterionId);
                        if ($criterion) {
                            $critArr = (array) $criterion;
                            $weightedSum += ($scoreArr['score'] * ($critArr['weight'] ?? 0));
                        }
                    }
                    return $weightedSum / $totalWeight;
                });
                
                $averageScore = $judgeScores->count() > 0 ? $judgeScores->avg() : 0;
            } else {
                // Normal average
                $averageScore = $distinctJudges > 0 ? $totalScoreSum / $distinctJudges : 0;
            }

            $progress = $totalPossible > 0 ? ($count / $totalPossible) * 100 : 0;

            return (object) [
                'id'            => $contestant['id'],
                'name'          => $contestant['name'],
                'number'        => $contestant['number'] ?? 0,
                'image_path'    => $contestant['image_path'] ?? null,
                'average_score' => round($averageScore, 2),
                'progress'      => $progress,
                'breakdown'     => $cScores->groupBy('criterion_id')->map(function ($group) use ($judgeCount) {
                    return $judgeCount > 0 ? $group->sum('score') / $judgeCount : 0;
                })
            ];
        })
        ->sortByDesc('average_score')
        ->values()
        ->map(function ($item, $index) {
            $item->rank = $index + 1;
            return $item;
        });

        return view('archive.show', compact('archive', 'rankings', 'criteria'));
    }

    /**
     * Download archived data as JSON.
     */
    public function download($id)
    {
        $archive = Archive::findOrFail($id);
        $fileName = str_replace(' ', '_', $archive->event_name) . '_archive.json';
        
        return response()->json($archive->data)
            ->withHeaders([
                'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            ]);
    }

    /**
     * Delete an archived event after passkey verification.
     */
    public function destroy(Request $request, $id)
    {
        $request->validate([
            'passkey' => 'required|string',
        ]);

        $savedPasskey = Setting::getValue('system_passkey', '1234');
        if ($request->passkey !== $savedPasskey) {
            return back()->with('error', 'Invalid administrative passkey. Deletion aborted.');
        }

        $archive = Archive::findOrFail($id);
        $archive->delete();

        return redirect()->route('archives.index')->with('success', 'Archive deleted successfully.');
    }
}
