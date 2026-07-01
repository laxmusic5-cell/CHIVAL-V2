<?php

namespace Database\Factories;

use App\Models\Addon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AddonFactory extends Factory
{
    protected $model = Addon::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->randomElement([
            'Wax',
            'Ceramic Spray',
            'Glass Treatment',
            'Vacuum Extra',
            'Tire Shine',
        ]);

        return [
            'code' => Str::slug($name, '_'),
            'name' => $name,
            'description' => $this->faker->sentence(10),
            'price' => $this->faker->numberBetween(15000, 75000),
            'is_active' => true,
        ];
    }
}
