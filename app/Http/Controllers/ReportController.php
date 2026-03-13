<?php

namespace App\Http\Controllers;

use App\Models\Contestant;
use App\Models\User;
use App\Models\Score;
use App\Models\Criterion;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display a listing of rankings and summaries.
     */
    public function index()
    {
        $activeEvent = \App\Models\Event::current();
        
        if (!$activeEvent) {
            $contestants = collect([]);
            $judgeCount = 0;
            $criteriaCount = 0;
            return view('reports.index', compact('contestants', 'judgeCount', 'criteriaCount'));
        }

        $judgeCount = User::where('role', User::ROLE_JUDGE)->count();
        $criteriaCount = $activeEvent->criteria()->count();
        $contestants = $activeEvent->rankings();

        return view('reports.index', compact('contestants', 'judgeCount', 'criteriaCount'));
    }

    /**
     * Export rankings to Excel.
     */
    public function export()
    {
        $activeEvent = \App\Models\Event::current();
        if (!$activeEvent) {
            return back()->with('error', 'No active event found to export.');
        }

        $judges      = User::where('role', User::ROLE_JUDGE)->get();
        $criteria    = $activeEvent->criteria;
        $contestants = $activeEvent->rankings();

        // Rankings are already assigned in $activeEvent->rankings()

        // Build scoreMap: [contestantId][judgeId][criterionId] => score
        // Optimization: Scope to active event contestants to avoid OOM and archive leaks
        $contestantIds = $contestants->pluck('id');
        $allScores = Score::whereIn('contestant_id', $contestantIds)->get()->groupBy('contestant_id');
        
        $scoreMap  = [];
        foreach ($allScores as $contestantId => $contestantScores) {
            foreach ($contestantScores as $s) {
                $scoreMap[$contestantId][$s->judge_id][$s->criterion_id] = $s->score;
            }
        }

        $filename = 'Rankings_' . now()->format('Y-m-d_H-i-s') . '.xls';

        $html  = "<html xmlns:o='urn:schemas-microsoft-com:office:office' ";
        $html .= "xmlns:x='urn:schemas-microsoft-com:office:excel' ";
        $html .= "xmlns='http://www.w3.org/TR/REC-html40'>";
        $html .= "<head><meta http-equiv='content-type' content='application/vnd.ms-excel; charset=UTF-8'></head><body>";

        // ── Summary Sheet ──────────────────────────────────────────────
        $html .= "<table><tr><td colspan='10' style='font-size:16pt;font-weight:bold;'>Competition Final Rankings</td></tr>";
        $html .= "<tr><td colspan='10'>Generated: " . now()->format('F j, Y  H:i') . "</td></tr><tr></tr>";

        // Headers
        $html .= "<tr style='background:#4f46e5;color:#fff;font-weight:bold;'>";
        $html .= "<td>Rank</td><td>No.</td><td>Participant</td>";
        foreach ($judges as $judge) {
            $html .= "<td>" . htmlspecialchars($judge->name) . "</td>";
        }
        $html .= "<td>Avg Score</td><td>Total Score</td><td>Completion</td></tr>";

        foreach ($contestants as $c) {
            $bgColor = $c->rank == 1 ? '#fef3c7' : ($c->rank == 2 ? '#f1f5f9' : ($c->rank == 3 ? '#fff7ed' : '#ffffff'));
            $html .= "<tr style='background:{$bgColor};'>";
            $html .= "<td>" . ($c->rank) . "</td>";
            $html .= "<td>" . htmlspecialchars($c->number) . "</td>";
            $html .= "<td style='font-weight:bold;'>" . htmlspecialchars($c->name) . "</td>";

            $formula = Setting::getValue('tabulation_formula', 'normal');
            $totalWeight = $criteria->sum('weight');

            foreach ($judges as $judge) {
                $judgeScoresMap = $scoreMap[$c->id][$judge->id] ?? [];
                
                if (empty($judgeScoresMap)) {
                    $html .= "<td>N/A</td>";
                    continue;
                }

                if ($formula === 'weighted' && $totalWeight > 0) {
                    $weightedSum = 0;
                    foreach ($judgeScoresMap as $criterionId => $scoreValue) {
                        $criterion = $criteria->firstWhere('id', $criterionId);
                        if ($criterion) {
                            $weightedSum += ($scoreValue * $criterion->weight);
                        }
                    }
                    $judgeValue = $weightedSum / $totalWeight;
                } else {
                    $judgeValue = array_sum($judgeScoresMap) / count($judgeScoresMap);
                }
                
                $html .= "<td>" . round($judgeValue, 4) . "</td>";
            }

            $html .= "<td style='font-weight:bold;color:#4f46e5;'>" . number_format($c->average_score, 4) . "</td>";
            $html .= "<td>" . number_format($c->total_score, 4) . "</td>";
            $html .= "<td>" . $c->progress . "%</td>";
            $html .= "</tr>";
        }
        $html .= "</table>";

        // ── Per-Criterion Breakdown ─────────────────────────────────────
        $html .= "<br><br><table><tr><td colspan='20' style='font-size:14pt;font-weight:bold;'>Score Breakdown by Criterion &amp; Judge</td></tr><tr></tr>";

        // Build header: Contestant | Criterion | Judge1 | Judge2 | …
        $html .= "<tr style='background:#4f46e5;color:#fff;font-weight:bold;'>";
        $html .= "<td>Rank</td><td>Participant</td><td>Criterion</td>";
        foreach ($judges as $judge) {
            $html .= "<td>" . htmlspecialchars($judge->name) . "</td>";
        }
        $html .= "<td>Criterion Avg</td></tr>";

        foreach ($contestants as $c) {
            $first = true;
            foreach ($criteria as $criterion) {
                $html .= "<tr>";
                if ($first) {
                    $html .= "<td style='font-weight:bold;'>" . $c->rank . "</td>";
                    $html .= "<td style='font-weight:bold;'>" . htmlspecialchars($c->name) . "</td>";
                    $first = false;
                } else {
                    $html .= "<td></td><td></td>";
                }
                $html .= "<td>" . htmlspecialchars($criterion->name) . " (wt: " . htmlspecialchars($criterion->weight) . ")</td>";

                $criterionScores = [];
                foreach ($judges as $judge) {
                    $score = $scoreMap[$c->id][$judge->id][$criterion->id] ?? null;
                    $html .= "<td>" . ($score !== null ? number_format($score, 2) : '—') . "</td>";
                    if ($score !== null) $criterionScores[] = $score;
                }
                $criterionAvg = count($criterionScores)
                    ? number_format(array_sum($criterionScores) / count($criterionScores), 4)
                    : '—';
                $html .= "<td style='font-weight:bold;'>{$criterionAvg}</td>";
                $html .= "</tr>";
            }
        }

        $html .= "</table></body></html>";

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', "attachment; filename=\"$filename\"")
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Display the score breakdown for a specific contestant.
     */
    public function show(Contestant $contestant)
    {
        $criteria = $contestant->event ? $contestant->event->criteria : collect([]);

        // Scope judges to only those who scored contestants in this event
        $eventContestantIds = $contestant->event
            ? $contestant->event->contestants()->pluck('id')
            : collect([]);
        $judgeIds = Score::whereIn('contestant_id', $eventContestantIds)
            ->pluck('judge_id')
            ->unique();
        $judges = User::where('role', User::ROLE_JUDGE)
            ->whereIn('id', $judgeIds)
            ->get();

        $scoreMap = Score::where('contestant_id', $contestant->id)
            ->get()
            ->groupBy('judge_id')
            ->map(fn($group) => $group->keyBy('criterion_id'));

        return view('reports.show', compact('contestant', 'judges', 'criteria', 'scoreMap'));
    }
}
