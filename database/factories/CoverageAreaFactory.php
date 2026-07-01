<?php

namespace Database\Factories;

use App\Models\CoverageArea;
use Illuminate\Database\Eloquent\Factories\Factory;

class CoverageAreaFactory extends Factory
{
    protected $model = CoverageArea::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement(['Area A', 'Area B', 'Area C']),
            'fee' => $this->faker->randomElement([0, 25000, 50000]),
            'note' => $this->faker->optional()->sentence(8),
            'requires_admin_approval' => false,
            'is_active' => true,
        ];
    }
}
