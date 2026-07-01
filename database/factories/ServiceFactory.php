<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->randomElement([
            'Exterior Wash',
            'Interior Cleaning',
            'Full Detailing',
            'Engine Bay Cleaning',
            'Premium Detailing',
        ]);

        return [
            'code' => Str::slug($name, '_'),
            'name' => $name,
            'description' => $this->faker->sentence(10),
            'is_active' => true,
        ];
    }
}
