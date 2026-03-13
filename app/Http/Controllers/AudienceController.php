<?php

namespace App\Http\Controllers;

use App\Models\Contestant;
use App\Models\Criterion;
use App\Models\User;
use Illuminate\Http\Request;

class AudienceController extends Controller
{
    /**
     * Display the public leaderboard.
     */
    public function index()
    {
        $isVisible = \App\Models\Setting::getValue('leaderboard_visible', '0');
        $activeEvent = \App\Models\Event::current();
        
        // If no active event, check for the most recently concluded one
        $displayEvent = $activeEvent;
        if (!$displayEvent) {
            $displayEvent = \App\Models\Event::where('status', \App\Models\Event::STATUS_CONCLUDED)
                ->orderBy('concluded_at', 'desc')
                ->first();
        }

        $eventName = $displayEvent ? $displayEvent->name : \App\Models\Setting::getValue('event_name', 'Tabulator Competition');

        // Show standings if:
        // 1. Leaderboard is visible AND there is an active event
        // 2. OR there is a concluded event (always show final results)
        $shouldShow = ($isVisible === '1' && $displayEvent);

        if (!$shouldShow || !$displayEvent) {
            return view('audience.standby', compact('eventName'));
        }

        $rankings = $displayEvent->rankings();

        $leaderboardFilter = \App\Models\Setting::getValue('leaderboard_filter', '10');
        if (is_numeric($leaderboardFilter)) {
            $rankings = $rankings->take((int)$leaderboardFilter);
        }

        $isConcluded = $displayEvent->status === \App\Models\Event::STATUS_CONCLUDED;

        return view('audience.index', compact('rankings', 'eventName', 'isConcluded'));
    }

    /**
     * Get the current leaderboard visibility status.
     */
    public function status()
    {
        $isVisible = \App\Models\Setting::getValue('leaderboard_visible', '0') === '1';
        $activeEvent = \App\Models\Event::current();
        
        // If no active event, check if there's a concluded one to show
        if (!$activeEvent) {
            // Respect visibility settings even if the event is concluded
        }

        return response()->json([
            'visible' => $isVisible
        ]);
    }
}
