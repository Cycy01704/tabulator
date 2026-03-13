<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PreAddEventDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_committee_can_create_contestant_for_pending_event()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $event = Event::create(['name' => 'Pending Event', 'status' => Event::STATUS_PENDING]);

        $response = $this->actingAs($admin)
            ->post(route('contestants.store'), [
                'name' => 'John Doe',
                'number' => 1,
                'event_id' => $event->id,
            ]);

        $response->assertRedirect(route('contestants.index', ['event_id' => $event->id]));
        $this->assertDatabaseHas('contestants', [
            'name' => 'John Doe',
            'event_id' => $event->id,
        ]);
    }

    public function test_committee_can_create_criterion_for_pending_event()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $event = Event::create(['name' => 'Pending Event', 'status' => Event::STATUS_PENDING]);

        $response = $this->actingAs($admin)
            ->post(route('criteria.store'), [
                'name' => 'Vocals',
                'weight' => 50,
                'event_id' => $event->id,
                'grades' => [
                    ['label' => 'Good', 'score' => 10],
                ],
            ]);

        $response->assertRedirect(route('criteria.index', ['event_id' => $event->id]));
        $this->assertDatabaseHas('criteria', [
            'name' => 'Vocals',
            'event_id' => $event->id,
        ]);
    }
}
