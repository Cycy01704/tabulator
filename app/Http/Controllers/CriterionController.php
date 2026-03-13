<?php

namespace App\Http\Controllers;

use App\Models\Criterion;
use App\Models\Grade;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Event;
use App\Imports\CriterionImport;
use App\Exports\CriterionTemplateExport;
use Maatwebsite\Excel\Facades\Excel;

class CriterionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $eventId = $request->query('event_id');
        $activeEvent = $eventId ?Event::find($eventId) : Event::current();

        $query = Criterion::with('grades');

        if ($activeEvent) {
            $query->where('event_id', $activeEvent->id);
        }
        else if (!$eventId) {
            $query->whereRaw('1=0');
        }

        $criteria = $query->get();
        $passkey = Setting::getValue('leaderboard_passkey', '1234');
        $securityEnabled = Setting::getValue('triple_layer_security', '1') == '1';
        return view('criteria.index', compact('criteria', 'passkey', 'securityEnabled', 'activeEvent'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $eventId = $request->query('event_id');
        $activeEvent = $eventId ?Event::find($eventId) : Event::current();

        if (!$activeEvent) {
            return redirect()->route('events.index')->with('error', 'You must select or start an event before configuring criteria.');
        }

        $formula = Setting::getValue('tabulation_formula', 'normal');
        return view('criteria.create', compact('activeEvent', 'formula'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $eventId = $request->input('event_id');
        $activeEvent = $eventId ?Event::find($eventId) : Event::current();

        if (!$activeEvent) {
            return redirect()->route('events.index')->with('error', 'You must select or start an event before configuring criteria.');
        }

        $formula = Setting::getValue('tabulation_formula', 'normal');
        $weightRule = $formula === 'weighted' ? 'required|numeric|min:0.01|max:100' : 'nullable|numeric|min:0';

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'weight' => $weightRule,
            'grades' => 'required|array|min:1',
            'grades.*.label' => 'required|string|max:255',
            'grades.*.score' => 'required|numeric|min:0',
            'event_id' => 'required|exists:events,id',
        ]);

        DB::transaction(function () use ($request, $activeEvent) {
            $data = $request->only('name', 'description', 'weight', 'event_id');

            $criterion = Criterion::create($data);

            foreach ($request->grades as $gradeData) {
                $criterion->grades()->create($gradeData);
            }
        });

        return redirect()->route('criteria.index', ['event_id' => $activeEvent->id])
            ->with('success', 'Criterion created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Criterion $criterion)
    {
        $criterion->load('grades');
        return view('criteria.show', compact('criterion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Criterion $criterion)
    {
        $criterion->load('grades');
        $formula = Setting::getValue('tabulation_formula', 'normal');
        return view('criteria.edit', compact('criterion', 'formula'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Criterion $criterion)
    {
        if ($criterion->event && $criterion->event->status === \App\Models\Event::STATUS_CONCLUDED) {
            return back()->with('error', 'Cannot modify data of a concluded event.');
        }
        $formula = Setting::getValue('tabulation_formula', 'normal');
        $weightRule = $formula === 'weighted' ? 'required|numeric|min:0.01|max:100' : 'nullable|numeric|min:0';

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'weight' => $weightRule,
            'grades' => 'required|array|min:1',
            'grades.*.label' => 'required|string|max:255',
            'grades.*.score' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $criterion) {
            $criterion->update($request->only('name', 'description', 'weight'));

            // Simple sync: delete and recreate grades
            $criterion->grades()->delete();
            foreach ($request->grades as $gradeData) {
                $criterion->grades()->create($gradeData);
            }
        });

        return redirect()->route('criteria.index')
            ->with('success', 'Criterion updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Criterion $criterion)
    {
        if ($criterion->event && $criterion->event->status === \App\Models\Event::STATUS_CONCLUDED) {
            return back()->with('error', 'Cannot delete data of a concluded event.');
        }
        $criterion->delete();

        return redirect()->route('criteria.index')
            ->with('success', 'Criterion deleted successfully.');
    }
    /**
     * Import criteria from Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file'     => 'required|mimes:xlsx,xls,csv|max:5120',
            'event_id' => 'required|exists:events,id',
        ]);

        $event = Event::find($request->event_id);
        if ($event && $event->status === Event::STATUS_CONCLUDED) {
            return back()->with('error', 'Cannot import criteria into a concluded event.');
        }

        try {
            Excel::import(new CriterionImport($request->event_id), $request->file('file'));
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

        return back()->with('success', 'Criteria imported successfully.');
    }

    /**
     * Download the criteria import template.
     */
    public function downloadTemplate()
    {
        return Excel::download(new CriterionTemplateExport, 'criteria_template.xlsx');
    }
}
