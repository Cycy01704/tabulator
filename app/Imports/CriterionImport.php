<?php

namespace App\Imports;

use App\Models\Criterion;
use App\Models\Grade;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\DB;

class CriterionImport implements ToModel, WithHeadingRow, WithValidation
{
    private $eventId;

    public function __construct($eventId)
    {
        $this->eventId = $eventId;
    }

    /**
     * Each row creates one Criterion + its pipe-separated grades.
     * Expected columns: name, description, weight, grades
     * grades format: "Excellent:100|Good:80|Fair:60"
     */
    public function model(array $row)
    {
        $criterion = Criterion::create([
            'name'        => $row['name'],
            'description' => $row['description'] ?? null,
            'weight'      => isset($row['weight']) && is_numeric($row['weight']) ? (float) $row['weight'] : null,
            'event_id'    => $this->eventId,
        ]);

        // Parse grades: "Label:Score|Label:Score"
        if (!empty($row['grades'])) {
            $gradeEntries = explode('|', $row['grades']);
            foreach ($gradeEntries as $entry) {
                $parts = explode(':', trim($entry));
                if (count($parts) === 2) {
                    Grade::create([
                        'criterion_id' => $criterion->id,
                        'label'        => trim($parts[0]),
                        'score'        => is_numeric(trim($parts[1])) ? (float) trim($parts[1]) : 0,
                    ]);
                }
            }
        }

        // Return null because we already created the model above
        return null;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'weight'      => ['nullable', 'numeric', 'min:0', 'max:100'],
            'grades'      => ['required', 'string'],
        ];
    }
}
