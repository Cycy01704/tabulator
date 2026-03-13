<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    /**
     * Display a listing of the events.
     */
    public function index()
    {
        $events = Event::withCount(['contestants', 'criteria'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('events.index', compact('events'));
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'expected_at' => 'nullable|date',
        ]);

        Event::create([
            'name' => $request->name,
            'expected_at' => $request->expected_at ? \Carbon\Carbon::parse($request->expected_at) : null,
            'status' => Event::STATUS_PENDING,
        ]);

        return redirect()->route('events.index')->with('success', 'Event created successfully.');
    }

    /**
     * Start the specified event.
     */
    public function start(Event $event)
    {
        if ($event->status !== Event::STATUS_PENDING) {
            return back()->with('error', 'Only pending events can be started.');
        }

        // Check if there's already an active event
        $activeEvent = Event::where('status', Event::STATUS_ACTIVE)->first();
        if ($activeEvent) {
            return back()->with('error', 'An event is already active. Please conclude it first.');
        }

        DB::transaction(function () use ($event) {
            $event->update([
                'status' => Event::STATUS_ACTIVE,
                'started_at' => now(),
            ]);

            // Sync with global system setting atomically within transaction
            Setting::updateOrCreate(
                ['key' => 'event_name'],
                ['value' => $event->name]
            );
        });

        return redirect()->route('dashboard')->with('success', "Event '{$event->name}' started successfully.");
    }

    /**
     * Delete a pending event.
     */
    public function destroy(Event $event)
    {
        if ($event->status !== Event::STATUS_PENDING) {
            return back()->with('error', 'Only pending events can be deleted.');
        }

        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }
}
