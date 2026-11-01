<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => config('app.demo_admin_email')],
            [
                'full_name' => 'Demo Administrator',
                'username' => 'demo_admin',
                'password' => Hash::make(Str::random(48)),
                'role' => 'Admin',
                'access_level' => 'View,Download,Print',
                'status' => 'Active',
                'profile_photo_path' => null,
                'date_time_created' => now(),
            ]
        );
    }
}
