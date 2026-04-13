<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate([
            'email' => 'admin@autonest.online',
        ], [
            'name' => 'Admin User',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        $this->call(SiteSettingSeeder::class);

        Car::factory()->count(12)->create();
    }
}
