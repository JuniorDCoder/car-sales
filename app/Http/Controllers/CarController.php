<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CarController extends Controller
{
    public function index(Request $request): Response
    {
        $carsQuery = Car::query()->available();
        $selectedMake = $request->string('make')->toString();

        $carsQuery
            ->when($request->string('search')->toString() !== '', function ($query) use ($request): void {
                $search = $request->string('search')->toString();
                $query->where(function ($subQuery) use ($search): void {
                    $subQuery
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('make', 'like', "%{$search}%")
                        ->orWhere('model', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('make'), fn ($query) => $query->where('make', $request->string('make')->toString()))
            ->when($request->filled('model'), fn ($query) => $query->where('model', $request->string('model')->toString()))
            ->when($request->filled('body_type'), fn ($query) => $query->whereIn('body_type', (array) $request->input('body_type', [])))
            ->when($request->filled('fuel_type'), fn ($query) => $query->whereIn('fuel_type', (array) $request->input('fuel_type', [])))
            ->when($request->filled('transmission'), fn ($query) => $query->whereIn('transmission', (array) $request->input('transmission', [])))
            ->when($request->filled('condition'), fn ($query) => $query->whereIn('condition', (array) $request->input('condition', [])))
            ->when($request->filled('min_price'), fn ($query) => $query->where('price', '>=', (int) $request->input('min_price')))
            ->when($request->filled('max_price'), fn ($query) => $query->where('price', '<=', (int) $request->input('max_price')))
            ->when($request->filled('min_year'), fn ($query) => $query->where('year', '>=', (int) $request->input('min_year')))
            ->when($request->filled('max_year'), fn ($query) => $query->where('year', '<=', (int) $request->input('max_year')))
            ->when($request->filled('max_mileage'), fn ($query) => $query->where('mileage', '<=', (int) $request->input('max_mileage')))
            ->when($request->boolean('featured_only'), fn ($query) => $query->where('is_featured', true));

        match ($request->string('sort')->toString()) {
            'price_asc' => $carsQuery->orderBy('price'),
            'price_desc' => $carsQuery->orderByDesc('price'),
            'mileage_asc' => $carsQuery->orderBy('mileage'),
            'year_desc' => $carsQuery->orderByDesc('year'),
            default => $carsQuery->latest(),
        };

        $availableCars = Car::query()->available();

        $modelOptionsQuery = Car::query()->available()->orderBy('model')->distinct()->select('model');

        if ($selectedMake !== '') {
            $modelOptionsQuery->where('make', $selectedMake);
        }

        return Inertia::render('Cars/Index', [
            'cars' => $carsQuery->paginate(12)->withQueryString(),
            'filters' => $request->only([
                'search',
                'make',
                'model',
                'body_type',
                'fuel_type',
                'transmission',
                'condition',
                'min_price',
                'max_price',
                'min_year',
                'max_year',
                'max_mileage',
                'featured_only',
                'sort',
            ]),
            'makes' => Car::query()->available()->distinct('make')->orderBy('make')->pluck('make')->values(),
            'models' => $modelOptionsQuery->pluck('model')->values(),
            'bodyTypeOptions' => Car::query()->available()->orderBy('body_type')->distinct()->pluck('body_type')->values(),
            'fuelTypeOptions' => Car::query()->available()->orderBy('fuel_type')->distinct()->pluck('fuel_type')->values(),
            'transmissionOptions' => Car::query()->available()->orderBy('transmission')->distinct()->pluck('transmission')->values(),
            'conditionOptions' => Car::query()->available()->orderBy('condition')->distinct()->pluck('condition')->values(),
            'priceRange' => [
                'min' => (int) ($availableCars->min('price') ?? 0),
                'max' => (int) ($availableCars->max('price') ?? 0),
            ],
            'yearRange' => [
                'min' => (int) ($availableCars->min('year') ?? now()->year),
                'max' => (int) ($availableCars->max('year') ?? now()->year),
            ],
        ]);
    }

    public function show(Car $car): Response
    {
        $car->increment('view_count');
        $car->refresh();

        $similarCars = Car::query()
            ->available()
            ->where('make', $car->make)
            ->whereKeyNot($car->getKey())
            ->latest()
            ->take(4)
            ->get();

        if ($similarCars->count() < 4) {
            $needed = 4 - $similarCars->count();

            $fallbackCars = Car::query()
                ->available()
                ->where('body_type', $car->body_type)
                ->whereKeyNot($car->getKey())
                ->whereNotIn('id', $similarCars->pluck('id'))
                ->latest()
                ->take($needed)
                ->get();

            $similarCars = $similarCars->concat($fallbackCars)->values();
        }

        return Inertia::render('Cars/Show', [
            'car' => $car,
            'similarCars' => $similarCars,
            'whatsappNumber' => SiteSetting::get('whatsapp_number', ''),
            'contactEmail' => SiteSetting::get('contact_email', ''),
            'whatsappEnabled' => SiteSetting::get('whatsapp_enabled', '1') === '1',
            'whatsappMessage' => SiteSetting::get('whatsapp_message', ''),
        ]);
    }
}
