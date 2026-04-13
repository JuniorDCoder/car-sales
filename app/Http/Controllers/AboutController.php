<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Inertia\Inertia;
use Inertia\Response;

class AboutController extends Controller
{
    public function index(): Response
    {
        $cars = Car::query()
            ->available()
            ->latest()
            ->take(12)
            ->get();

        $carsWithImages = $cars
            ->filter(fn (Car $car) => filled($car->images[0] ?? null))
            ->values();

        $aboutCars = $carsWithImages->isNotEmpty()
            ? $carsWithImages->take(6)->values()
            : $cars->take(6)->values();

        return Inertia::render('About', [
            'aboutCars' => $aboutCars,
            'teamStats' => [
                'happy_buyers' => '4,800+',
                'cars_delivered' => '9,200+',
                'dealer_rating' => '4.9/5',
            ],
        ]);
    }
}
