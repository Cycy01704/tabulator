<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CommitteeStaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'staff@tabulator.com'],
            [
                'name'     => 'Committee Staff',
                'password' => Hash::make('password'),
                'role'     => User::ROLE_COMMITTEE,
            ]
        );
    }
}
