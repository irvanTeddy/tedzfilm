<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'], // Identifier unik
            [
                'name'     => 'Tedz Admin',
                'password' => Hash::make('password123'), // Password untuk login admin
            ]
        );
    }
}
