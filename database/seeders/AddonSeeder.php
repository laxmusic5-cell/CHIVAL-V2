<?php

namespace Database\Seeders;

use App\Models\Addon;
use Illuminate\Database\Seeder;

class AddonSeeder extends Seeder
{
    public function run(): void
    {
        $addons = [
            ['code' => 'wax', 'name' => 'Wax', 'description' => 'Protective wax finish for added shine and protection.', 'price' => 15000, 'is_active' => true],
            ['code' => 'ceramic_spray', 'name' => 'Ceramic Spray', 'description' => 'Ceramic spray coating for long-lasting water beading.', 'price' => 45000, 'is_active' => true],
            ['code' => 'glass_treatment', 'name' => 'Glass Treatment', 'description' => 'Rain-repellent glass treatment for clearer visibility.', 'price' => 30000, 'is_active' => true],
            ['code' => 'vacuum_extra', 'name' => 'Vacuum Extra', 'description' => 'Extra vacuum pass for deep-cleaned upholstery and carpet.', 'price' => 20000, 'is_active' => true],
            ['code' => 'tire_shine', 'name' => 'Tire Shine', 'description' => 'Tire shine application for a polished tire look.', 'price' => 20000, 'is_active' => true],
        ];

        foreach ($addons as $addon) {
            Addon::firstOrCreate(['code' => $addon['code']], $addon);
        }
    }
}
