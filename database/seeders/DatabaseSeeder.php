<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'rucisoft@gmail.com')],
            ['name' => 'Admin Rucisoft', 'password' => env('ADMIN_PASSWORD', 'password')],
        );

        $this->call(ContentSeeder::class);
    }
}
