<?php

namespace Database\Factories;

use App\Models\VehicleType;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleTypeFactory extends Factory
{
    protected $model = VehicleType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement(['Sedan', 'Hatchback', 'SUV', 'MPV', 'Pickup']),
            'size' => $this->faker->randomElement(['Compact', 'Mid-size', 'Full-size', 'Large']),
            'description' => $this->faker->sentence(8),
            'is_active' => true,
        ];
    }
}
