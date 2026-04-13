<?php

use App\Models\Car;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('cars index only lists available cars', function () {
    $availableCar = Car::factory()->create(['title' => 'Available Car', 'is_sold' => false]);
    $soldCar = Car::factory()->create(['title' => 'Sold Car', 'is_sold' => true]);

    $response = $this->get(route('cars.index'));

    $response->assertOk();
    $response->assertSee($availableCar->title);
    $response->assertDontSee($soldCar->title);
});

test('car show resolves via slug and increments view count', function () {
    $car = Car::factory()->create(['view_count' => 0]);

    $response = $this->get(route('cars.show', $car));

    $response->assertOk();
    expect($car->fresh()->view_count)->toBe(1);
});

