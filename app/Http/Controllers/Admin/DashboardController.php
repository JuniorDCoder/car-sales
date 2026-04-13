<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'total_cars' => Car::query()->count(),
                'available_cars' => Car::query()->where('is_sold', false)->count(),
                'featured_cars' => Car::query()->where('is_featured', true)->count(),
                'sold_cars' => Car::query()->where('is_sold', true)->count(),
                'total_views' => (int) Car::query()->sum('view_count'),
            ],
        ]);
    }
}
