<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Models\Car;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('Admin/Cars/Index', [
            'cars' => Car::query()->latest()->paginate(15),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Cars/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCarRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_sold'] = $request->boolean('is_sold');

        if ($request->hasFile('images')) {
            $paths = [];
            $folder = 'cars/'.Str::uuid();

            foreach ((array) $request->file('images') as $image) {
                $paths[] = $image->store($folder, 'public');
            }

            $validated['images'] = $paths;
        }

        Car::query()->create($validated);

        return to_route('admin.cars.index')->with('success', 'Car created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Car $car): RedirectResponse
    {
        return to_route('admin.cars.edit', $car);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Car $car): Response
    {
        return Inertia::render('Admin/Cars/Edit', [
            'car' => $car,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCarRequest $request, Car $car): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_sold'] = $request->boolean('is_sold');

        $existingImages = array_values((array) ($validated['existing_images'] ?? $car->images ?? []));
        unset($validated['existing_images']);

        if ($request->hasFile('images')) {
            $folder = 'cars/'.Str::uuid();
            $uploaded = [];

            foreach ((array) $request->file('images') as $image) {
                $uploaded[] = $image->store($folder, 'public');
            }

            $validated['images'] = array_slice(array_merge($existingImages, $uploaded), 0, 10);
        } else {
            $validated['images'] = $existingImages;
        }

        $car->update($validated);

        return to_route('admin.cars.index')->with('success', 'Car updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car): RedirectResponse
    {
        $directories = collect((array) $car->images)
            ->map(fn (string $path): string => dirname($path))
            ->unique()
            ->values();

        foreach ($directories as $directory) {
            if ($directory !== '.') {
                Storage::disk('public')->deleteDirectory($directory);
            }
        }

        $car->delete();

        return back()->with('success', 'Car deleted successfully.');
    }
}
