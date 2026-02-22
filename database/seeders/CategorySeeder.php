<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name' => 'Family',
                'slug' => 'family',
                'description' => 'Family and relatives',
                'status' => true,
                'created_at' => now(),
            ],
            [
                'name' => 'Friends',
                'slug' => 'friends',
                'description' => 'Close friends and acquaintances',
                'status' => true,
                'created_at' => now(),
            ],
            [
                'name' => 'Colleagues',
                'slug' => 'colleagues',
                'description' => 'Work colleagues and professional contacts',
                'status' => true,
                'created_at' => now(),
            ],
            [
                'name' => 'Other',
                'slug' => 'other',
                'description' => 'Other contacts that do not fit into the above categories',
                'status' => true,
                'created_at' => now(),
            ],
        ]);
    }
}
