<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Seed a demo event and mark it as active.
     */
    public function run(): void
    {
        Event::updateOrCreate(
            ['name' => 'Annual Talent Showdown 2026'],
            [
                'status' => Event::STATUS_ACTIVE,
                'started_at' => now(),
            ]
        );
    }
}
