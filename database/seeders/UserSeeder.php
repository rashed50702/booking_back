<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('12345678'),
            'is_admin' => 1
        ]);
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('12345678'),
            'is_admin' => 0
        ]);
        User::create([
            'name' => 'David',
            'email' => 'david@example.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('12345678'),
            'is_admin' => 0
        ]);
        User::create([
            'name' => 'Jessica',
            'email' => 'jessica@example.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('12345678'),
            'is_admin' => 0
    ]);
    }
}
