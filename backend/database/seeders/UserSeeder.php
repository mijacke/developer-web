<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => 'password',
                'role' => 'admin',
                'is_blocked' => false,
                'is_approved' => true,
            ]
        );

        // Create regular user
        User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'password' => 'password',
                'role' => 'user',
                'is_blocked' => false,
                'is_approved' => true,
            ]
        );

        $this->command->info('Default users created:');
        $this->command->line('  Admin: admin@example.com / password');
        $this->command->line('  User:  user@example.com / password');
    }
}
