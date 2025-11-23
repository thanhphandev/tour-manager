<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Create Test User
        User::factory()->create([
            'name' => 'Phan Văn Thành',
            'email' => 'thanh041610@gmail.com',
            'password' => Hash::make('123'),
            'is_admin' => false,
            'avatar' => 'https://api.dicebear.com/9.x/adventurer/svg?seed=PhanVanThanh',
        ]);

        $this->call([
            DestinationSeeder::class,
            TourSeeder::class,
            TourImageSeeder::class,
            AdminUserSeeder::class,
            UserSeeder::class,
            BookingSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}
