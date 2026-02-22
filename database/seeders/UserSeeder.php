<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'hsabev',
                'email' => 'hsabev@sprintax.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'is_locked' => 0,
                'role' => '1',
            ],
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'is_locked' => 0,
                'role' => '0',
            ],
        ]);
    }
}
