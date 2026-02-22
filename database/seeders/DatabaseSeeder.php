<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        // Category::factory(10)->create();
        //  Contact::factory(50)->create();

        $this->call([
            CategorySeeder::class,
            UserSeeder::class,
        ]);
    }
}
