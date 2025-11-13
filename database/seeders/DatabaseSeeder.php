<?php

namespace Database\Seeders;

use App\Models\User;
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

        User::factory()->create([
            'name' => 'Phan Văn Thành',
            'email' => 'thanhphanvan@gmail.com',
            'password' => bcrypt('123'),
            'is_admin' => true,
        ]);

        $this->call([
            DestinationSeeder::class,
            TourSeeder::class,
            TourImageSeeder::class,
            SettingSeeder::class,
            EmailTemplateSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
