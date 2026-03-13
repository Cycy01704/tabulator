<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Seed default system settings.
     */
    public function run(): void
    {
        $defaults = [
            'event_name'            => 'Tabulator Competition',
            'leaderboard_passkey'   => '1234',
            'leaderboard_visible'   => '0',
            'triple_layer_security' => '1',
        ];

        foreach ($defaults as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
