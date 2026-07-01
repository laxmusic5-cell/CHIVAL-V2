<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['code' => 'exterior_wash', 'name' => 'Exterior Wash', 'description' => 'Thorough exterior cleaning with premium soap and rinse.', 'is_active' => true],
            ['code' => 'interior_cleaning', 'name' => 'Interior Cleaning', 'description' => 'Deep interior vacuuming and wipe-down for a fresh cabin.', 'is_active' => true],
            ['code' => 'full_detailing', 'name' => 'Full Detailing', 'description' => 'Complete interior and exterior detailing for showroom finish.', 'is_active' => true],
            ['code' => 'engine_bay_cleaning', 'name' => 'Engine Bay Cleaning', 'description' => 'Safe engine bay wash to remove grease and grime.', 'is_active' => true],
            ['code' => 'premium_detailing', 'name' => 'Premium Detailing', 'description' => 'Top-tier detailing with protective coatings and finish polish.', 'is_active' => true],
        ];

        foreach ($services as $service) {
            Service::firstOrCreate(['code' => $service['code']], $service);
        }
    }
}
