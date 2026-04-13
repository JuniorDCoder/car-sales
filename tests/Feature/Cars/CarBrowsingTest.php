<?php

use App\Models\Car;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

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

test('cars index supports advanced filtering combinations', function () {
    Car::factory()->create([
        'title' => 'Toyota RAV4 Adventure',
        'make' => 'Toyota',
        'model' => 'RAV4',
        'body_type' => 'SUV',
        'fuel_type' => 'Petrol',
        'transmission' => 'Automatic',
        'condition' => 'Used',
        'year' => 2022,
        'mileage' => 42000,
        'price' => 28000,
        'is_featured' => true,
        'is_sold' => false,
    ]);

    Car::factory()->create([
        'title' => 'Honda Civic Base',
        'make' => 'Honda',
        'model' => 'Civic',
        'body_type' => 'Sedan',
        'fuel_type' => 'Petrol',
        'transmission' => 'Manual',
        'condition' => 'Used',
        'year' => 2018,
        'mileage' => 78000,
        'price' => 13000,
        'is_featured' => false,
        'is_sold' => false,
    ]);

    $response = $this->get(route('cars.index', [
        'make' => 'Toyota',
        'model' => 'RAV4',
        'body_type' => ['SUV'],
        'transmission' => ['Automatic'],
        'min_year' => 2020,
        'max_mileage' => 50000,
        'featured_only' => 1,
    ]));

    $response
        ->assertOk()
        ->assertSee('Toyota RAV4 Adventure')
        ->assertDontSee('Honda Civic Base');
});

test('about page renders real inventory highlights', function () {
    $listedCar = Car::factory()->create([
        'title' => 'Mazda CX-5 Signature',
        'is_sold' => false,
        'images' => ['cars/mazda-cx5.jpg'],
    ]);

    $response = $this->get(route('about'));

    $response
        ->assertOk()
        ->assertSee($listedCar->title);
});

test('car show page receives contact email and whatsapp toggle from settings', function () {
    SiteSetting::query()->updateOrCreate(['key' => 'contact_email'], [
        'key' => 'contact_email',
        'value' => 'sales@autonest.com',
        'type' => 'email',
        'label' => 'Contact Email Address',
        'group' => 'contact',
    ]);

    SiteSetting::query()->updateOrCreate(['key' => 'whatsapp_enabled'], [
        'key' => 'whatsapp_enabled',
        'value' => '0',
        'type' => 'boolean',
        'label' => 'Enable WhatsApp Buttons',
        'group' => 'contact',
    ]);

    SiteSetting::query()->updateOrCreate(['key' => 'whatsapp_number'], [
        'key' => 'whatsapp_number',
        'value' => '+15550001111',
        'type' => 'phone',
        'label' => 'WhatsApp Number',
        'group' => 'contact',
    ]);

    $car = Car::factory()->create();

    $this->get(route('cars.show', $car))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Cars/Show')
            ->where('contactEmail', 'sales@autonest.com')
            ->where('whatsappEnabled', false)
        );
});
