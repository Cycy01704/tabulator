<?php

namespace App\Http\Controllers;

use App\Models\Contestant;
use App\Models\Criterion;
use App\Models\Score;
use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        }

        if ($user->isCommittee()) {
            return $this->committeeDashboard();
        }

        return $this->judgeDashboard();
    }

    private function adminDashboard()
    {
        $activeEvent = Event::current();

        $activeEventScores = $activeEvent ? Score::whereIn('contestant_id', $activeEvent->contestants()->pluck('id'))->count() : 0;
        $activeJudgesCount = $activeEvent ? Score::whereIn('contestant_id', $activeEvent->contestants()->pluck('id'))->distinct('judge_id')->count('judge_id') : 0;

        $stats = [
            'total_users'        => User::count(),
            'total_contestants'  => $activeEvent ? $activeEvent->contestants()->count() : 0,
            'total_criteria'     => $activeEvent ? $activeEvent->criteria()->count() : 0,
            'total_judges'       => User::where('role', User::ROLE_JUDGE)->count(),
            'total_committees'   => User::where('role', User::ROLE_COMMITTEE)->count(),
            'total_scores'       => Score::count(),
            'total_archives'     => \App\Models\Archive::count(),
            'active_event_scores' => $activeEventScores,
            'active_judges_count' => $activeJudgesCount,
        ];

        $leaderboardFilter = \App\Models\Setting::getValue('leaderboard_filter', '10');

        // System performance overview (scoped to active event)
        $topPerformers = $activeEvent ? $activeEvent->rankings() : collect([]);
        if (is_numeric($leaderboardFilter)) {
            $topPerformers = $topPerformers->take((int)$leaderboardFilter);
        }
        $topPerformers = $topPerformers->map(function($item) {
            $item->avg = round($item->average_score, 2);
            return $item;
        });

        // Judge Progress for Admin View
        $judges = User::where('role', User::ROLE_JUDGE)->get();
        $totalContestants = $activeEvent ? $activeEvent->contestants()->count() : 0;
        $totalCriteria = $activeEvent ? $activeEvent->criteria()->count() : 0;
        $totalExpectedItems = $totalContestants * $totalCriteria;

        $judgeProgress = $judges->map(function($judge) use ($activeEvent, $totalExpectedItems) {
            if (!$activeEvent || $totalExpectedItems === 0) {
                return (object) ['id' => $judge->id, 'name' => $judge->name, 'progress' => 0, 'scored' => 0, 'total' => 0, 'is_done' => false];
            }
            $scoredCount = Score::where('judge_id', $judge->id)
                ->whereIn('contestant_id', $activeEvent->contestants()->pluck('id'))
                ->count();
            return (object) [
                'id' => $judge->id,
                'name' => $judge->name,
                'scored' => $scoredCount,
                'total' => $totalExpectedItems,
                'progress' => ($scoredCount / $totalExpectedItems) * 100,
                'is_done' => $scoredCount >= $totalExpectedItems && $totalExpectedItems > 0
            ];
        });

        $securityEnabled = \App\Models\Setting::getValue('triple_layer_security', '1');
        $leaderboardVisible = \App\Models\Setting::getValue('leaderboard_visible', '0');
        
        return view('dashboards.admin', compact('stats', 'topPerformers', 'activeEvent', 'securityEnabled', 'leaderboardVisible', 'judgeProgress', 'leaderboardFilter'));
    }

    private function getCommitteeData()
    {
        $activeEvent = Event::current();

        $activeEventScores = $activeEvent ? Score::whereIn('contestant_id', $activeEvent->contestants()->pluck('id'))->count() : 0;
        $activeJudgesCount = $activeEvent ? Score::whereIn('contestant_id', $activeEvent->contestants()->pluck('id'))->distinct('judge_id')->count('judge_id') : 0;

        $stats = [
            'contestants' => $activeEvent ? $activeEvent->contestants()->count() : 0,
            'criteria'    => $activeEvent ? $activeEvent->criteria()->count() : 0,
            'judges'      => User::where('role', User::ROLE_JUDGE)->count(),
            'total_users' => User::count(),
            'total_scores' => Score::count(),
            'active_event_scores' => $activeEventScores,
            'active_judges_count' => $activeJudgesCount,
            'total_contestants' => $activeEvent ? $activeEvent->contestants()->count() : 0,
            'total_archives' => \App\Models\Archive::count(),
        ];

        $leaderboardFilter = \App\Models\Setting::getValue('leaderboard_filter', '10');

        $rankings = $activeEvent ? $activeEvent->rankings() : collect([]);
        if (is_numeric($leaderboardFilter)) {
            $rankings = $rankings->take((int)$leaderboardFilter);
        }
        $rankings = $rankings->map(function($item) {
            $item->avg = round($item->average_score, 2);
            return $item;
        });

        $topPerformers = $rankings; // Mirror for admin dashboard

        // Judge Progress Calculations
        $judges = User::where('role', User::ROLE_JUDGE)->get();
        $totalContestants = $stats['contestants'];
        $totalCriteria = $stats['criteria'];
        $totalExpectedItems = $totalContestants * $totalCriteria;

        $judgeProgress = $judges->map(function($judge) use ($activeEvent, $totalExpectedItems) {
            if (!$activeEvent || $totalExpectedItems === 0) {
                return (object) ['name' => $judge->name, 'progress' => 0, 'scored' => 0, 'total' => 0, 'is_done' => false];
            }

            $scoredCount = Score::where('judge_id', $judge->id)
                ->whereIn('contestant_id', $activeEvent->contestants()->pluck('id'))
                ->count();

            $progress = ($scoredCount / $totalExpectedItems) * 100;

            return (object) [
                'id' => $judge->id,
                'name' => $judge->name,
                'scored' => $scoredCount,
                'total' => $totalExpectedItems,
                'progress' => $progress,
                'is_done' => $scoredCount >= $totalExpectedItems && $totalExpectedItems > 0
            ];
        });

        // Calculate overall system performance (average completion of all judges)
        $totalCompletion = $judgeProgress->count() > 0 ? $judgeProgress->avg('progress') : 0;
        $stats['overall_completion'] = round($totalCompletion);

        $leaderboardVisible = \App\Models\Setting::getValue('leaderboard_visible', '0');
        $securityEnabled = \App\Models\Setting::getValue('triple_layer_security', '1');
        $isJudge = auth()->user()->isJudge();
        
        return [
            'stats'              => $stats,
            'rankings'           => $isJudge ? collect([]) : $rankings,
            'judgeProgress'      => $isJudge ? collect([]) : $judgeProgress,
            'leaderboardVisible' => $leaderboardVisible,
            'securityEnabled'    => $securityEnabled,
            'activeEvent'        => $activeEvent,
            'topPerformers'      => $isJudge ? collect([]) : $topPerformers,
            'leaderboardFilter'  => $leaderboardFilter
        ];
    }

    private function committeeDashboard()
    {
        $data = $this->getCommitteeData();
        return view('dashboards.committee', $data);
    }

    /**
     * API Endpoint for real-time dashboard updates.
     */
    public function data()
    {
        return response()->json($this->getCommitteeData());
    }

    private function judgeDashboard()
    {
        $judge      = Auth::user();
        $activeEvent = Event::current();
        
        $criteria    = $activeEvent ? $activeEvent->criteria : collect([]);
        $contestants = $activeEvent ? $activeEvent->contestants : collect([]);

        $totalExpected  = $contestants->count() * $criteria->count();
        $totalScored    = Score::where('judge_id', $judge->id)
            ->whereIn('contestant_id', $contestants->pluck('id'))
            ->count();
        $progressPct    = $totalExpected > 0 ? round(($totalScored / $totalExpected) * 100) : 0;

        // Per-contestant progress for the judge
        $contestantProgress = $contestants->map(function ($contestant) use ($judge, $criteria) {
            $scored = Score::where('judge_id', $judge->id)
                ->where('contestant_id', $contestant->id)
                ->count();
            $total  = $criteria->count();

            return (object) [
                'id'          => $contestant->id,
                'name'        => $contestant->name,
                'number'      => $contestant->number,
                'image_path'  => $contestant->image_path,
                'scored'      => $scored,
                'total'       => $total,
                'done'        => $scored >= $total && $total > 0,
            ];
        });

        $stats = [
            'contestants'    => $contestants->count(),
            'criteria'       => $criteria->count(),
            'scored'         => Score::where('judge_id', $judge->id)
                ->whereIn('contestant_id', $contestants->pluck('id'))
                ->distinct('contestant_id')
                ->count('contestant_id'),
            'progress'       => $progressPct,
        ];
        
        $activeEvent = Event::current();
        
        return view('dashboards.judge', compact('stats', 'contestantProgress', 'activeEvent'));
    }
}
