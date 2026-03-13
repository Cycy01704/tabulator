<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Seed the initial system administrator.
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
    }
}
