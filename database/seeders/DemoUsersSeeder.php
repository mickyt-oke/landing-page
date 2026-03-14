<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DemoUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Avoid duplicates across multiple runs.
        $users = [
            [
                'name' => 'Standard User',
                'email' => 'user@example.com',
                'password' => 'Password123!',
                'role' => User::ROLE_USER,
            ],
            [
                'name' => 'Reviewer User',
                'email' => 'reviewer@example.com',
                'password' => 'Password123!',
                'role' => User::ROLE_REVIEWER,
            ],
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => 'Password123!',
                'role' => User::ROLE_ADMIN,
            ],
            [
                'name' => 'Super Admin User',
                'email' => 'superadmin@example.com',
                'password' => 'Password123!',
                'role' => User::ROLE_SUPERADMIN,
            ],
        ];

        foreach ($users as $data) {
            User::query()->updateOrCreate(
                ['email' => $data['email']],
                $data
            );
        }
    }
}
