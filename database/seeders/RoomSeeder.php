<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Room::factory()
        //     ->count(10)
        //     ->create();
        Room::create(['name' => 'Executive Suite']);
        Room::create(['name' => 'President Suite']);
        Room::create(['name' => 'Murphy']);
        Room::create(['name' => 'Mini Suite']);
        Room::create(['name' => 'Walker Cantrell']);
        Room::create(['name' => 'Luxury Suite']);
        Room::create(['name' => 'Accessible Room']);
    }
}
