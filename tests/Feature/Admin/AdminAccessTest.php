<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('guests are redirected when visiting admin area', function () {
    $response = $this->get(route('admin.dashboard'));

    $response->assertRedirect(route('login'));
});

test('non admin users receive forbidden in admin area', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $response = $this->actingAs($user)->get(route('admin.dashboard'));

    $response->assertForbidden();
});

test('admin users can access admin dashboard', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Dashboard')
            ->has('stats.total_cars')
            ->has('stats.available_cars')
            ->has('stats.featured_cars')
            ->has('stats.sold_cars')
            ->has('stats.total_views')
            ->has('stats.inventory_value')
            ->has('stats.average_price')
            ->has('stats.average_mileage')
            ->has('stats.sell_through_rate')
            ->has('stats.featured_rate')
            ->has('topMakes')
            ->has('bodyTypeMix')
            ->has('topViewedCars')
            ->has('recentListings')
        );
});
