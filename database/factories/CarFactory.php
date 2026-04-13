<?php

namespace Database\Factories;

use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $makesAndModels = [
            'Toyota' => ['Camry', 'RAV4', 'Corolla', 'Highlander'],
            'Mercedes-Benz' => ['C-Class', 'E-Class', 'GLC', 'GLE'],
            'Honda' => ['Accord', 'Civic', 'CR-V', 'Pilot'],
            'Hyundai' => ['Elantra', 'Sonata', 'Tucson', 'Santa Fe'],
            'Nissan' => ['Altima', 'Sentra', 'Rogue', 'Pathfinder'],
            'Ford' => ['F-150', 'Escape', 'Explorer', 'Mustang'],
            'BMW' => ['3 Series', '5 Series', 'X3', 'X5'],
            'Chevrolet' => ['Malibu', 'Equinox', 'Tahoe', 'Silverado'],
            'Jeep' => ['Wrangler', 'Cherokee', 'Grand Cherokee', 'Compass'],
            'Tesla' => ['Model 3', 'Model Y', 'Model S', 'Model X'],
        ];

        $make = $this->faker->randomElement(array_keys($makesAndModels));
        $model = $this->faker->randomElement($makesAndModels[$make]);
        $year = $this->faker->numberBetween(2015, 2025);

        return [
            'title' => sprintf('%d %s %s', $year, $make, $model),
            'make' => $make,
            'model' => $model,
            'year' => $year,
            'price' => $this->faker->numberBetween(8000, 120000),
            'mileage' => $this->faker->numberBetween(0, 150000),
            'fuel_type' => $this->faker->randomElement(['Petrol', 'Diesel', 'Electric', 'Hybrid']),
            'transmission' => $this->faker->randomElement(['Manual', 'Automatic']),
            'body_type' => $this->faker->randomElement(['Sedan', 'SUV', 'Pickup', 'Hatchback', 'Coupe', 'Van', 'Minivan']),
            'color' => $this->faker->safeColorName(),
            'condition' => $this->faker->randomElement(['New', 'Used', 'Certified Pre-Owned']),
            'description' => $this->faker->paragraphs(3, true),
            'features' => $this->faker->randomElements([
                'Leather Seats',
                'Bluetooth',
                'Apple CarPlay',
                'Android Auto',
                'Backup Camera',
                'Sunroof',
                'Lane Assist',
                'Adaptive Cruise Control',
            ], $this->faker->numberBetween(3, 6)),
            'images' => [$this->faker->imageUrl(1280, 720, 'cars', true)],
            'is_featured' => $this->faker->boolean(25),
            'is_sold' => $this->faker->boolean(15),
            'view_count' => $this->faker->numberBetween(0, 600),
        ];
    }
}
