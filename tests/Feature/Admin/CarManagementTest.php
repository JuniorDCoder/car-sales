<?php

use App\Models\Car;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('admin can create a car with multiple images', function () {
    Storage::fake('public');

    $admin = User::factory()->create(['is_admin' => true]);

    $payload = [
        'title' => '2024 Toyota RAV4',
        'make' => 'Toyota',
        'model' => 'RAV4',
        'year' => 2024,
        'price' => 32000,
        'mileage' => 12000,
        'fuel_type' => 'Petrol',
        'transmission' => 'Automatic',
        'body_type' => 'SUV',
        'color' => 'Silver',
        'condition' => 'Used',
        'description' => 'A well-maintained SUV with complete service history and premium trim package.',
        'features' => ['Backup Camera', 'Leather Seats'],
        'images' => [
            UploadedFile::fake()->image('front.jpg'),
            UploadedFile::fake()->image('interior.jpg'),
            UploadedFile::fake()->image('rear.jpg'),
        ],
        'is_featured' => true,
        'is_sold' => false,
    ];

    $response = $this->actingAs($admin)->post(route('admin.cars.store'), $payload);

    $response->assertRedirect(route('admin.cars.index'));

    $car = Car::query()->where('title', '2024 Toyota RAV4')->firstOrFail();

    expect($car->images)->toHaveCount(3);

    foreach ($car->images as $path) {
        Storage::disk('public')->assertExists($path);
    }
});

test('admin car index supports filters', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    Car::factory()->create([
        'title' => 'Toyota Corolla Special',
        'make' => 'Toyota',
        'model' => 'Corolla',
        'is_sold' => false,
        'is_featured' => true,
    ]);

    Car::factory()->create([
        'title' => 'Ford Focus Sale',
        'make' => 'Ford',
        'model' => 'Focus',
        'is_sold' => true,
        'is_featured' => false,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.cars.index', [
        'search' => 'Toyota',
        'status' => 'available',
        'featured_only' => 1,
    ]));

    $response
        ->assertOk()
        ->assertSee('Toyota Corolla Special')
        ->assertDontSee('Ford Focus Sale');
});

test('admin can remove an existing car image during edit', function () {
    Storage::fake('public');

    $admin = User::factory()->create(['is_admin' => true]);
    $car = Car::factory()->create([
        'title' => 'Image Removal Car',
        'images' => ['cars/existing/front.jpg', 'cars/existing/rear.jpg'],
    ]);

    Storage::disk('public')->put('cars/existing/front.jpg', 'fake-front');
    Storage::disk('public')->put('cars/existing/rear.jpg', 'fake-rear');

    $response = $this->actingAs($admin)->put(route('admin.cars.update', $car), [
        'title' => $car->title,
        'make' => $car->make,
        'model' => $car->model,
        'year' => $car->year,
        'price' => $car->price,
        'mileage' => $car->mileage,
        'fuel_type' => $car->fuel_type,
        'transmission' => $car->transmission,
        'body_type' => $car->body_type,
        'color' => $car->color,
        'condition' => $car->condition,
        'description' => $car->description,
        'features' => $car->features,
        'existing_images' => ['cars/existing/front.jpg'],
        'is_featured' => $car->is_featured,
        'is_sold' => $car->is_sold,
    ]);

    $response->assertRedirect(route('admin.cars.index'));

    $car->refresh();

    expect($car->images)->toBe(['cars/existing/front.jpg']);

    Storage::disk('public')->assertExists('cars/existing/front.jpg');
    Storage::disk('public')->assertMissing('cars/existing/rear.jpg');
});
