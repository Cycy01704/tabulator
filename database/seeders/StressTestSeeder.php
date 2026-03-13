<?php

namespace Database\Seeders;

use App\Models\Contestant;
use App\Models\Criterion;
use App\Models\Event;
use App\Models\Score;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StressTestSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure Active Event
        $event = Event::where('status', 'active')->first();
        if (!$event) {
            $event = Event::create([
                'name' => 'Stress Test Championship 2026',
                'description' => 'A high-volume performance test event.',
                'status' => 'active',
                'tabulation_formula' => 'normal'
            ]);
        }

        $this->command->info("Seeding Stress Test Data for Event: {$event->name}");

        // 2. Create Judges (20 judges)
        $this->command->info("Creating 20 judges...");
        $judges = [];
        for ($i = 1; $i <= 20; $i++) {
            $judges[] = User::updateOrCreate(
                ['email' => "judge.stress.$i@tabulator.com"],
                [
                    'name' => "Stress Judge #$i",
                    'password' => Hash::make('password'),
                    'role' => User::ROLE_JUDGE,
                ]
            );
        }

        // 3. Create Criteria (5 criteria)
        $this->command->info("Creating 5 criteria...");
        $criteria = [];
        $criteriaData = [
            ['name' => 'Technical Mastery', 'weight' => 20],
            ['name' => 'Artistic Impression', 'weight' => 20],
            ['name' => 'Stage Presence', 'weight' => 20],
            ['name' => 'Complexity', 'weight' => 20],
            ['name' => 'Audience Engagement', 'weight' => 20],
        ];

        foreach ($criteriaData as $data) {
            $criteria[] = Criterion::updateOrCreate(
                ['name' => $data['name'], 'event_id' => $event->id],
                [
                    'weight' => $data['weight'],
                    'event_id' => $event->id,
                ]
            );
        }

        // 4. Create Contestants (100 contestants)
        $this->command->info("Creating 100 contestants...");
        $contestants = [];
        for ($i = 1; $i <= 100; $i++) {
            $contestants[] = Contestant::updateOrCreate(
                ['number' => $i + 100, 'event_id' => $event->id], 
                [
                    'name' => "Stress Contestant #$i",
                    'description' => "High volume test contestant #$i",
                    'event_id' => $event->id,
                    'gender' => ($i % 2 == 0 ? 'Female' : 'Male'),
                ]
            );
        }

        // 5. Generate Scores (Judges * Contestants * Criteria = 20 * 100 * 5 = 10,000 scores)
        $this->command->info("Generating 10,000 score entries... This may take a moment.");
        
        $scoresToInsert = [];
        $batchSize = 500;
        $count = 0;

        foreach ($judges as $judge) {
            foreach ($contestants as $contestant) {
                foreach ($criteria as $criterion) {
                    $scoresToInsert[] = [
                        'judge_id' => $judge->id,
                        'contestant_id' => $contestant->id,
                        'criterion_id' => $criterion->id,
                        'score' => rand(75, 100),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    $count++;

                    if (count($scoresToInsert) >= $batchSize) {
                        Score::insert($scoresToInsert);
                        $scoresToInsert = [];
                        $this->command->info("Inserted $count scores...");
                    }
                }
            }
        }

        if (count($scoresToInsert) > 0) {
            Score::insert($scoresToInsert);
        }

        $this->command->info("Stress test seeding complete. Total scores: $count");
    }
}
