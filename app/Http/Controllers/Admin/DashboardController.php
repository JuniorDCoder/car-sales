<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $totalCars = Car::query()->count();
        $soldCars = Car::query()->where('is_sold', true)->count();
        $availableCars = Car::query()->where('is_sold', false)->count();
        $featuredCars = Car::query()->where('is_featured', true)->count();

        $totalViews = (int) Car::query()->sum('view_count');
        $inventoryValue = (int) Car::query()->where('is_sold', false)->sum('price');
        $averagePrice = (int) round((float) Car::query()->where('is_sold', false)->avg('price'));
        $averageMileage = (int) round((float) Car::query()->avg('mileage'));

        $sellThroughRate = $totalCars > 0
            ? round(($soldCars / $totalCars) * 100, 1)
            : 0.0;

        $featuredRate = $totalCars > 0
            ? round(($featuredCars / $totalCars) * 100, 1)
            : 0.0;

        /** @var Collection<int, array{label: string, count: int, percentage: float}> $topMakes */
        $topMakes = Car::query()
            ->selectRaw('make as label, COUNT(*) as count')
            ->groupBy('make')
            ->orderByDesc('count')
            ->limit(5)
            ->get()
            ->map(fn (Car $car): array => [
                'label' => (string) $car->getAttribute('label'),
                'count' => (int) $car->getAttribute('count'),
                'percentage' => $totalCars > 0
                    ? round((((int) $car->getAttribute('count')) / $totalCars) * 100, 1)
                    : 0.0,
            ]);

        /** @var Collection<int, array{label: string, count: int, percentage: float}> $bodyTypeMix */
        $bodyTypeMix = Car::query()
            ->selectRaw('body_type as label, COUNT(*) as count')
            ->groupBy('body_type')
            ->orderByDesc('count')
            ->limit(5)
            ->get()
            ->map(fn (Car $car): array => [
                'label' => (string) $car->getAttribute('label'),
                'count' => (int) $car->getAttribute('count'),
                'percentage' => $totalCars > 0
                    ? round((((int) $car->getAttribute('count')) / $totalCars) * 100, 1)
                    : 0.0,
            ]);

        $topViewedCars = Car::query()
            ->select(['id', 'title', 'slug', 'price', 'view_count', 'is_sold'])
            ->orderByDesc('view_count')
            ->limit(6)
            ->get();

        $recentListings = Car::query()
            ->select(['id', 'title', 'slug', 'price', 'created_at', 'is_featured', 'is_sold'])
            ->latest()
            ->limit(6)
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'total_cars' => $totalCars,
                'available_cars' => $availableCars,
                'featured_cars' => $featuredCars,
                'sold_cars' => $soldCars,
                'total_views' => $totalViews,
                'inventory_value' => $inventoryValue,
                'average_price' => $averagePrice,
                'average_mileage' => $averageMileage,
                'sell_through_rate' => $sellThroughRate,
                'featured_rate' => $featuredRate,
            ],
            'topMakes' => $topMakes,
            'bodyTypeMix' => $bodyTypeMix,
            'topViewedCars' => $topViewedCars,
            'recentListings' => $recentListings,
        ]);
    }
}
