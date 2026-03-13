<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;

class ContestantTemplateExport implements WithHeadings
{
    /**
    * @return array
    */
    public function headings(): array
    {
        return [
            'name',
            'number',
            'age',
            'address',
            'gender',
            'dob',
            'occupation',
            'contact_number',
            'email',
            'hobbies',
            'motto',
            'description',
        ];
    }
}
