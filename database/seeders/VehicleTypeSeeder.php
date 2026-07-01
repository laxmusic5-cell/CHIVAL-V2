<?php

namespace Database\Seeders;

use App\Models\VehicleType;
use Illuminate\Database\Seeder;

class VehicleTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Sedan', 'size' => 'Mid-size', 'description' => 'Comfortable sedan for city and highway travel.', 'is_active' => true],
            ['name' => 'Hatchback', 'size' => 'Compact', 'description' => 'Compact hatchback ideal for urban driving.', 'is_active' => true],
            ['name' => 'SUV', 'size' => 'Large', 'description' => 'Spacious SUV built for passengers and cargo.', 'is_active' => true],
            ['name' => 'MPV', 'size' => 'Large', 'description' => 'Multi-purpose vehicle for family comfort and flexibility.', 'is_active' => true],
            ['name' => 'Pickup', 'size' => 'Full-size', 'description' => 'Pickup truck designed for heavy-duty hauling.', 'is_active' => true],
        ];

        foreach ($types as $type) {
            VehicleType::firstOrCreate(['name' => $type['name']], $type);
        }
    }
}
