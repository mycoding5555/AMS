<?php

namespace Database\Seeders;

use App\Models\Apartment;
use App\Models\Floor;
use Illuminate\Database\Seeder;

class ApartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $floorPrefixes = [
            'Ground Floor' => 'A',
            'First Floor' => 'B',
            'Second Floor' => 'C',
            'Third Floor' => 'D',
        ];

        $monthlyRent = 1500; // Default rent

        foreach ($floorPrefixes as $floorName => $prefix) {
            $floor = Floor::where('name', $floorName)->first();

            if ($floor) {
                for ($i = 1; $i <= 8; $i++) {
                    $roomNumber = str_pad($i, 2, '0', STR_PAD_LEFT); // 01-08
                    $apartmentNumber = $prefix . $roomNumber; // A01-A08, B01-B08, etc.

                    Apartment::create([
                        'floor_id' => $floor->id,
                        'room_number' => $roomNumber,
                        'apartment_number' => $apartmentNumber,
                        'monthly_rent' => $monthlyRent,
                        'status' => 'available',
                        'supervisor_id' => null,
                    ]);
                }
            }
        }
    }
}

