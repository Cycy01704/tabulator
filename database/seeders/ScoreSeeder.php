<?php

namespace Database\Seeders;

use App\Models\Contestant;
use App\Models\Criterion;
use App\Models\Score;
use App\Models\User;
use Illuminate\Database\Seeder;

class ScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $judges = User::where('role', User::ROLE_JUDGE)->get();
        $contestants = Contestant::all();
        $criteria = Criterion::with('grades')->get();

        foreach ($contestants as $contestant) {
            foreach ($judges as $judge) {
                foreach ($criteria as $criterion) {
                    // Pick a random grade for this criterion
                    $grade = $criterion->grades->random();

                    Score::updateOrCreate(
                        [
                            'judge_id' => $judge->id,
                            'contestant_id' => $contestant->id,
                            'criterion_id' => $criterion->id,
                        ],
                        [
                            'score' => $grade->score,
                        ]
                    );
                }
            }
        }
    }
}
