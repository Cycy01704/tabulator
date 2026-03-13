<?php

namespace Database\Seeders;

use App\Models\Contestant;
use App\Models\Event;
use Illuminate\Database\Seeder;

class ContestantSeeder extends Seeder
{
    /**
     * Seed contestants linked to the active event.
     */
    public function run(): void
    {
        $event = Event::current();

        if (!$event) {
            $this->command->warn('No active event found. Skipping ContestantSeeder.');
            return;
        }

        $contestants = [
            ['name' => 'Alice Johnson', 'number' => 1, 'description' => 'A talented solo singer from New York.'],
            ['name' => 'The Beatmakers', 'number' => 2, 'description' => 'A dynamic dance crew with high energy.'],
            ['name' => 'Charlie Smith', 'number' => 3, 'description' => 'An acoustic guitarist and songwriter.'],
            ['name' => 'Sparkle Harmony', 'number' => 4, 'description' => 'A vocal group known for tight harmonies.'],
            ['name' => 'Nova Dance', 'number' => 5, 'description' => 'Contemporary dance performers.'],
        ];

        foreach ($contestants as $data) {
            Contestant::updateOrCreate(
                ['number' => $data['number'], 'event_id' => $event->id],
                [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'event_id' => $event->id,
                ]
            );
        }
    }
}
