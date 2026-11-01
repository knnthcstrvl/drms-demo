<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call([
            // DashboardTableSeeder::class,
            StudentsTableSeeder::class,
        ]);

        \App\Models\User::factory(10)->create();
        $this->call(DemoAdminSeeder::class);
        // \App\Models\Student::factory(10)->create();
    }
}
