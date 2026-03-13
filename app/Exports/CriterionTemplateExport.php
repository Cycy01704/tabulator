<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;

class CriterionTemplateExport implements WithHeadings, FromCollection
{
    /**
     * Return one sample row so the template is useful.
     */
    public function collection(): Collection
    {
        return collect([
            [
                'Crowd Interaction',
                'How well the contestant engages the audience.',
                '25',
                'Excellent:100|Good:80|Fair:60|Poor:40',
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            'name',
            'description',
            'weight',
            'grades',
        ];
    }
}
