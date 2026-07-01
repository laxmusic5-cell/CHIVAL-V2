<?php

namespace Database\Seeders;

use App\Models\CoverageArea;
use Illuminate\Database\Seeder;

class CoverageAreaSeeder extends Seeder
{
    public function run(): void
    {
        $areas = [
            ['name' => 'Area A', 'fee' => 0, 'note' => 'Free coverage area for central zone.', 'requires_admin_approval' => false, 'is_active' => true],
            ['name' => 'Area B', 'fee' => 25000, 'note' => 'Standard coverage area with moderate surcharge.', 'requires_admin_approval' => false, 'is_active' => true],
            ['name' => 'Area C', 'fee' => 50000, 'note' => 'Extended coverage area for outlying locations.', 'requires_admin_approval' => false, 'is_active' => true],
        ];

        foreach ($areas as $area) {
            CoverageArea::firstOrCreate(['name' => $area['name']], $area);
        }
    }
}
