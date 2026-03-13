<?php

namespace App\Imports;

use App\Models\Contestant;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;

class ContestantImport implements ToModel, WithHeadingRow, WithValidation
{
    private $eventId;

    public function __construct($eventId)
    {
        $this->eventId = $eventId;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Contestant([
            'name'           => $row['name'],
            'number'         => $row['number'],
            'age'            => $row['age'] ?? null,
            'address'        => $row['address'] ?? null,
            'gender'         => $row['gender'] ?? null,
            'dob'            => $row['dob'] ?? null,
            'occupation'     => $row['occupation'] ?? null,
            'contact_number' => $row['contact_number'] ?? null,
            'email'          => $row['email'] ?? null,
            'hobbies'        => $row['hobbies'] ?? null,
            'motto'          => $row['motto'] ?? null,
            'description'    => $row['description'] ?? null,
            'event_id'       => $this->eventId,
        ]);
    }

    public function rules(): array
    {
        return [
            'name'   => ['required', 'string', 'max:255'],
            'number' => [
                'required',
                'integer',
                Rule::unique('contestants', 'number')->where(function ($query) {
                    return $query->where('event_id', $this->eventId);
                }),
            ],
            'description'    => ['nullable', 'string'],
            'age'            => ['nullable', 'string', 'max:50'],
            'address'        => ['nullable', 'string', 'max:255'],
            'gender'         => ['nullable', 'string', 'in:Male,Female,Other'],
            'dob'            => ['nullable', 'date'],
            'occupation'     => ['nullable', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'email'          => ['nullable', 'email', 'max:255'],
            'hobbies'        => ['nullable', 'string'],
            'motto'          => ['nullable', 'string', 'max:255'],
        ];
    }
}
