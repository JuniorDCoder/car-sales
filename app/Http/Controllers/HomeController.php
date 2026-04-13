<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\SiteSetting;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Home', [
            'featuredCars' => Car::query()->featured()->available()->latest()->take(6)->get(),
            'latestCars' => Car::query()->available()->latest()->take(8)->get(),
            'heroHeadline' => SiteSetting::get('hero_headline', 'Find Your Perfect Car Today'),
            'heroSubheadline' => SiteSetting::get('hero_subheadline', 'Premium vehicles curated for every lifestyle.'),
            'whatsappNumber' => SiteSetting::get('whatsapp_number', ''),
            'whatsappMessage' => SiteSetting::get('whatsapp_message', "Hello, I'm interested in one of your cars."),
        ]);
    }
}
