<?php

namespace App\Http\Controllers;

use App\Models\Contestant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;
use App\Models\Event;
use App\Imports\ContestantImport;
use App\Exports\ContestantTemplateExport;
use Maatwebsite\Excel\Facades\Excel;

class ContestantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $eventId = $request->query('event_id');
        $activeEvent = $eventId ?Event::find($eventId) : Event::current();

        $query = Contestant::query();

        if ($activeEvent) {
            $query->where('event_id', $activeEvent->id);
        }
        else if (!$eventId) {
            $query->whereRaw('1=0');
        }

        $contestants = $query->orderBy('number')->paginate(10);
        $passkey = Setting::getValue('leaderboard_passkey', '1234');
        $securityEnabled = Setting::getValue('triple_layer_security', '1') == '1';
        $settings = Setting::all()->pluck('value', 'key');
        return view('contestants.index', compact('contestants', 'passkey', 'securityEnabled', 'activeEvent', 'settings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $eventId = $request->query('event_id');
        $activeEvent = $eventId ?Event::find($eventId) : Event::current();

        if (!$activeEvent) {
            return redirect()->route('events.index')->with('error', 'You must select or start an event before adding contestants.');
        }

        $settings = Setting::all()->pluck('value', 'key');
        return view('contestants.create', compact('activeEvent', 'settings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $eventId = $request->input('event_id');
        $activeEvent = $eventId ?Event::find($eventId) : Event::current();

        if (!$activeEvent) {
            return redirect()->route('events.index')->with('error', 'You must select or start an event before adding contestants.');
        }

        $settings = Setting::all()->pluck('value', 'key');
        
        $rules = [
            'name' => 'required|string|max:255',
            'event_id' => 'required|exists:events,id',
        ];

        if (($settings['field_contestant_number'] ?? '1') == '1') {
            $rules['number'] = 'required|integer|unique:contestants,number,NULL,id,event_id,' . $activeEvent->id;
        }

        if (($settings['field_contestant_age'] ?? '0') == '1') {
            $rules['age'] = 'required|string|max:50';
        }

        if (($settings['field_contestant_address'] ?? '0') == '1') {
            $rules['address'] = 'required|string|max:255';
        }

        if (($settings['field_contestant_image'] ?? '1') == '1') {
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        if (($settings['field_contestant_description'] ?? '1') == '1') {
            $rules['description'] = 'nullable|string';
        }

        if (($settings['field_contestant_gender'] ?? '0') == '1') {
            $rules['gender'] = 'required|string|in:Male,Female,Other';
        }

        if (($settings['field_contestant_dob'] ?? '0') == '1') {
            $rules['dob'] = 'required|date';
        }

        if (($settings['field_contestant_occupation'] ?? '0') == '1') {
            $rules['occupation'] = 'required|string|max:255';
        }

        if (($settings['field_contestant_contact'] ?? '0') == '1') {
            $rules['contact_number'] = 'required|string|max:20';
        }

        if (($settings['field_contestant_email'] ?? '0') == '1') {
            $rules['email'] = 'required|email|max:255';
        }

        if (($settings['field_contestant_hobbies'] ?? '0') == '1') {
            $rules['hobbies'] = 'required|string';
        }

        if (($settings['field_contestant_motto'] ?? '0') == '1') {
            $rules['motto'] = 'required|string|max:255';
        }

        $request->validate($rules);

        $data = $request->only([
            'name', 'number', 'age', 'address', 'description', 'gender', 
            'dob', 'occupation', 'contact_number', 'email', 'hobbies', 'motto', 'event_id'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('contestants', 'public');
            $data['image_path'] = $path;
        }

        Contestant::create($data);

        return redirect()->route('contestants.index', ['event_id' => $activeEvent->id])
            ->with('success', 'Contestant created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contestant $contestant)
    {
        return view('contestants.show', compact('contestant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contestant $contestant)
    {
        $contestant->load('event');
        $settings = Setting::all()->pluck('value', 'key');
        return view('contestants.edit', compact('contestant', 'settings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contestant $contestant)
    {
        if ($contestant->event && $contestant->event->status === \App\Models\Event::STATUS_CONCLUDED) {
            return back()->with('error', 'Cannot modify data of a concluded event.');
        }
        $settings = Setting::all()->pluck('value', 'key');
        
        $rules = [
            'name' => 'required|string|max:255',
        ];

        if (($settings['field_contestant_number'] ?? '1') == '1') {
            $rules['number'] = 'required|integer|unique:contestants,number,' . $contestant->id . ',id,event_id,' . $contestant->event_id;
        }

        if (($settings['field_contestant_age'] ?? '0') == '1') {
            $rules['age'] = 'required|string|max:50';
        }

        if (($settings['field_contestant_address'] ?? '0') == '1') {
            $rules['address'] = 'required|string|max:255';
        }

        if (($settings['field_contestant_image'] ?? '1') == '1') {
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        if (($settings['field_contestant_description'] ?? '1') == '1') {
            $rules['description'] = 'nullable|string';
        }

        if (($settings['field_contestant_gender'] ?? '0') == '1') {
            $rules['gender'] = 'required|string|in:Male,Female,Other';
        }

        if (($settings['field_contestant_dob'] ?? '0') == '1') {
            $rules['dob'] = 'required|date';
        }

        if (($settings['field_contestant_occupation'] ?? '0') == '1') {
            $rules['occupation'] = 'required|string|max:255';
        }

        if (($settings['field_contestant_contact'] ?? '0') == '1') {
            $rules['contact_number'] = 'required|string|max:20';
        }

        if (($settings['field_contestant_email'] ?? '0') == '1') {
            $rules['email'] = 'required|email|max:255';
        }

        if (($settings['field_contestant_hobbies'] ?? '0') == '1') {
            $rules['hobbies'] = 'required|string';
        }

        if (($settings['field_contestant_motto'] ?? '0') == '1') {
            $rules['motto'] = 'required|string|max:255';
        }

        $request->validate($rules);

        $data = $request->only([
            'name', 'number', 'age', 'address', 'description', 'gender', 
            'dob', 'occupation', 'contact_number', 'email', 'hobbies', 'motto'
        ]);
        
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($contestant->image_path) {
                Storage::disk('public')->delete($contestant->image_path);
            }
            $path = $request->file('image')->store('contestants', 'public');
            $data['image_path'] = $path;
        }

        $contestant->update($data);

        return redirect()->route('contestants.index')
            ->with('success', 'Contestant updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contestant $contestant)
    {
        if ($contestant->event && $contestant->event->status === \App\Models\Event::STATUS_CONCLUDED) {
            return back()->with('error', 'Cannot delete data of a concluded event.');
        }
        if ($contestant->image_path) {
            Storage::disk('public')->delete($contestant->image_path);
        }
        $contestant->delete();

        return redirect()->route('contestants.index')
            ->with('success', 'Contestant deleted successfully.');
    }

    /**
     * Import contestants from Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
            'event_id' => 'required|exists:events,id',
        ]);

        try {
            Excel::import(new ContestantImport($request->event_id), $request->file('file'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
            }
            return back()->with('error', 'Import failed: ' . implode(' | ', $errors));
        } catch (\Exception $e) {
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }

        return back()->with('success', 'Contestants imported successfully.');
    }

    /**
     * Download the contestant import template.
     */
    public function downloadTemplate()
    {
        return Excel::download(new ContestantTemplateExport, 'contestant_template.xlsx');
    }
}
