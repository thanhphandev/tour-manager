<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'thanhphanvan1610@gmail.com',
            'password' => Hash::make('123'),
            'is_admin' => true,
            'avatar' => 'https://api.dicebear.com/9.x/adventurer/svg?seed=AdminUser',
        ]);
    }
}
