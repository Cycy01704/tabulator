<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed users: admin, committee, and judges.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@tabulator.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
            ]
        );

        User::updateOrCreate(
            ['email' => 'committee@tabulator.com'],
            [
                'name' => 'Committee Head',
                'password' => Hash::make('password'),
                'role' => User::ROLE_COMMITTEE,
            ]
        );

        User::updateOrCreate(
            ['email' => 'judge1@tabulator.com'],
            [
                'name' => 'Judge Alpha',
                'password' => Hash::make('password'),
                'role' => User::ROLE_JUDGE,
            ]
        );

        User::updateOrCreate(
            ['email' => 'judge2@tabulator.com'],
            [
                'name' => 'Judge Bravo',
                'password' => Hash::make('password'),
                'role' => User::ROLE_JUDGE,
            ]
        );

        User::updateOrCreate(
            ['email' => 'judge3@tabulator.com'],
            [
                'name' => 'Judge Charlie',
                'password' => Hash::make('password'),
                'role' => User::ROLE_JUDGE,
            ]
        );
    }
}
