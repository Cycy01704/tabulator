<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use App\Models\Contestant;
use App\Models\Criterion;
use App\Models\Score;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScoringFormulaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create an admin
        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => User::ROLE_ADMIN,
        ]);

        // Create an active event
        $this->event = Event::create([
            'name' => 'Formula Test Event',
            'status' => Event::STATUS_ACTIVE,
        ]);

        // Create two criteria with different weights
        $this->criterion1 = Criterion::create([
            'name' => 'Criterion A',
            'weight' => 70, // 70%
            'event_id' => $this->event->id,
        ]);
        $this->criterion2 = Criterion::create([
            'name' => 'Criterion B',
            'weight' => 30, // 30%
            'event_id' => $this->event->id,
        ]);

        // Create a contestant
        $this->contestant = Contestant::create([
            'name' => 'Contestant 1',
            'number' => 1,
            'event_id' => $this->event->id,
        ]);

        // Create two judges
        $this->judge1 = User::create([
            'name' => 'Judge 1',
            'email' => 'judge1@example.com',
            'password' => bcrypt('password'),
            'role' => User::ROLE_JUDGE,
        ]);
        $this->judge2 = User::create([
            'name' => 'Judge 2',
            'email' => 'judge2@example.com',
            'password' => bcrypt('password'),
            'role' => User::ROLE_JUDGE,
        ]);

        // Assign scores
        // Judge 1: C1=10, C2=0 -> Normal Avg = 5, Weighted Avg = 7
        Score::create(['judge_id' => $this->judge1->id, 'contestant_id' => $this->contestant->id, 'criterion_id' => $this->criterion1->id, 'score' => 10]);
        Score::create(['judge_id' => $this->judge1->id, 'contestant_id' => $this->contestant->id, 'criterion_id' => $this->criterion2->id, 'score' => 0]);

        // Judge 2: C1=0, C2=10 -> Normal Avg = 5, Weighted Avg = 3
        Score::create(['judge_id' => $this->judge2->id, 'contestant_id' => $this->contestant->id, 'criterion_id' => $this->criterion1->id, 'score' => 0]);
        Score::create(['judge_id' => $this->judge2->id, 'contestant_id' => $this->contestant->id, 'criterion_id' => $this->criterion2->id, 'score' => 10]);
    }

    public function test_normal_average_formula()
    {
        Setting::setValue('tabulation_formula', 'normal');
        
        $rankings = $this->event->rankings();
        $this->assertEquals(1, $rankings->count());
        
        $contestant = $rankings->first();
        // Total scores: (10+0) + (0+10) = 20
        // Divided by 2 judges = 10 (Wait, my logic in setUp shows Judge 1 avg = 5, Judge 2 avg = 5)
        // Normal Average: (10+0+0+10) / 2 = 10
        $this->assertEquals(10, $contestant->average_score);
    }

    public function test_weighted_average_formula()
    {
        Setting::setValue('tabulation_formula', 'weighted');
        
        $rankings = $this->event->rankings();
        $this->assertEquals(1, $rankings->count());
        
        $contestant = $rankings->first();
        // Judge 1: (10 * 70 + 0 * 30) / 100 = 7
        // Judge 2: (0 * 70 + 10 * 30) / 100 = 3
        // Mean of judge averages: (7 + 3) / 2 = 5
        $this->assertEquals(5, $contestant->average_score);
    }

    public function test_admin_can_update_formula_setting()
    {
        $passkey = Setting::getValue('leaderboard_passkey', '1234');
        
        $response = $this->actingAs($this->admin)
            ->post(route('settings.update'), [
                'key' => 'tabulation_formula',
                'value' => 'weighted',
                'passkey' => $passkey,
            ]);

        $response->assertStatus(302); // Redirect back
        $this->assertEquals('weighted', Setting::getValue('tabulation_formula'));
    }

    public function test_criteria_weight_optional_in_normal_mode()
    {
        Setting::setValue('tabulation_formula', 'normal');
        
        $response = $this->actingAs($this->admin)
            ->post(route('criteria.store'), [
                'name' => 'New Criterion',
                'event_id' => $this->event->id,
                'grades' => [['label' => 'A', 'score' => 10]],
                // No weight provided
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('criteria', [
            'name' => 'New Criterion',
            'weight' => 0,
        ]);
    }

    public function test_criteria_weight_required_in_weighted_mode()
    {
        Setting::setValue('tabulation_formula', 'weighted');
        
        $response = $this->actingAs($this->admin)
            ->post(route('criteria.store'), [
                'name' => 'Weighted Criterion',
                'event_id' => $this->event->id,
                'grades' => [['label' => 'A', 'score' => 10]],
                // No weight provided
            ]);

        $response->assertSessionHasErrors('weight');
    }
}
