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
            ->when($request->filled('body_type'), fn ($query) => $query->whereIn('body_type', (array) $request->input('body_type', [])))
            ->when($request->filled('fuel_type'), fn ($query) => $query->whereIn('fuel_type', (array) $request->input('fuel_type', [])))
            ->when($request->filled('transmission'), fn ($query) => $query->where('transmission', $request->string('transmission')->toString()))
            ->when($request->filled('condition'), fn ($query) => $query->where('condition', $request->string('condition')->toString()))
            ->when($request->filled('min_price'), fn ($query) => $query->where('price', '>=', (int) $request->input('min_price')))
            ->when($request->filled('max_price'), fn ($query) => $query->where('price', '<=', (int) $request->input('max_price')));

        match ($request->string('sort')->toString()) {
            'price_asc' => $carsQuery->orderBy('price'),
            'price_desc' => $carsQuery->orderByDesc('price'),
            'mileage_asc' => $carsQuery->orderBy('mileage'),
            default => $carsQuery->latest(),
        };

        return Inertia::render('Cars/Index', [
            'cars' => $carsQuery->paginate(12)->withQueryString(),
            'filters' => $request->only([
                'search',
                'make',
                'body_type',
                'fuel_type',
                'transmission',
                'condition',
                'min_price',
                'max_price',
                'sort',
            ]),
            'makes' => Car::query()->available()->distinct('make')->orderBy('make')->pluck('make')->values(),
        ]);
    }

    public function show(Car $car): Response
    {
        $car->increment('view_count');
        $car->refresh();

        return Inertia::render('Cars/Show', [
            'car' => $car,
            'similarCars' => Car::query()
                ->available()
                ->where('make', $car->make)
                ->whereKeyNot($car->getKey())
                ->latest()
                ->take(4)
                ->get(),
            'whatsappNumber' => SiteSetting::get('whatsapp_number', ''),
            'contactEmail' => SiteSetting::get('contact_email', ''),
            'whatsappMessage' => SiteSetting::get('whatsapp_message', ''),
        ]);
    }
}
