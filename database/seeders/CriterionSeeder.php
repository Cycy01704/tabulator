<?php

namespace Database\Seeders;

use App\Models\Criterion;
use App\Models\Event;
use Illuminate\Database\Seeder;

class CriterionSeeder extends Seeder
{
    /**
     * Seed criteria with grades, linked to the active event.
     */
    public function run(): void
    {
        $event = Event::current();

        if (!$event) {
            $this->command->warn('No active event found. Skipping CriterionSeeder.');
            return;
        }

        $criteria = [
            [
                'name' => 'Vocal Execution',
                'description' => 'Technical accuracy, pitch, and vocal control.',
                'weight' => 35.00,
                'grades' => [
                    ['label' => 'Masterful', 'score' => 10.00],
                    ['label' => 'Proficient', 'score' => 8.00],
                    ['label' => 'Developing', 'score' => 6.00],
                    ['label' => 'Substandard', 'score' => 4.00],
                ]
            ],
            [
                'name' => 'Stage Artistry',
                'description' => 'Visual impact, movement, and emotional delivery.',
                'weight' => 25.00,
                'grades' => [
                    ['label' => 'Captivating', 'score' => 10.00],
                    ['label' => 'Engaging', 'score' => 7.50],
                    ['label' => 'Adequate', 'score' => 5.00],
                ]
            ],
            [
                'name' => 'Overall Impact',
                'description' => 'The X-factor and lasting impression.',
                'weight' => 40.00,
                'grades' => [
                    ['label' => 'Legendary', 'score' => 10.00],
                    ['label' => 'Memorable', 'score' => 8.00],
                    ['label' => 'Good', 'score' => 6.00],
                    ['label' => 'Forgettable', 'score' => 3.00],
                ]
            ],
        ];

        foreach ($criteria as $data) {
            $criterion = Criterion::updateOrCreate(
                ['name' => $data['name'], 'event_id' => $event->id],
                [
                    'description' => $data['description'],
                    'weight' => $data['weight'],
                    'event_id' => $event->id,
                ]
            );

            // Clean up old grades to avoid duplicates if re-running
            $criterion->grades()->delete();

            foreach ($data['grades'] as $gradeData) {
                $criterion->grades()->create($gradeData);
            }
        }
    }
}
