<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'username' => 'admin',
                'lastName' => 'Yuji',
                'firstName' => 'Itadori',
                'email' => 'ndbalboa.30@gmail.com',
                'password' => Hash::make('password'),
                'department' => null,
                'role' => 'admin',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
                'employee_id' => null,
            ],
            [
                'username' => 'secretary',
                'lastName' => 'Balboa',
                'firstName' => 'Nino',
                'email' => 'secretary@gmail.com',
                'password' => Hash::make('password'),
                'department' => null,
                'role' => 'secretary',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
                'employee_id' => null,
            ],
            [
                'username' => 'user1',
                'lastName' => 'Ann',
                'firstName' => 'Curtis',
                'email' => 'user@gmail.com',
                'password' => Hash::make('password'),
                'department' => null,
                'role' => 'user',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
                'employee_id' => null,
            ],
            [
                'username' => 'it101',
                'lastName' => 'Cena',
                'firstName' => 'Jhon',
                'email' => 'department@gmail.com',
                'password' => Hash::make('password'),
                'department' => null,
                'role' => 'department',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
                'employee_id' => null,
            ],
        ];

        foreach ($users as $user) {
            // Use updateOrInsert to prevent duplicates
            DB::table('users')->updateOrInsert(
                ['username' => $user['username']], // Unique constraint to check
                $user // Values to insert or update
            );
        }
    }
}
