<?php

namespace Database\Seeders;

use App\Models\Floor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FloorSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $floors = [
            ['name' => 'Ground Floor'],
            ['name' => 'First Floor'],
            ['name' => 'Second Floor'],
            ['name' => 'Third Floor'],
        ];

        foreach ($floors as $floor) {
            Floor::create($floor);
        }
    }
}
