<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Models\Car;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $query = Car::query()
            ->when($request->filled('search'), function ($builder) use ($request): void {
                $search = $request->string('search')->toString();
                $builder->where(function ($subQuery) use ($search): void {
                    $subQuery
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('make', 'like', "%{$search}%")
                        ->orWhere('model', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('make'), fn ($builder) => $builder->where('make', $request->string('make')->toString()))
            ->when($request->filled('model'), fn ($builder) => $builder->where('model', $request->string('model')->toString()))
            ->when($request->filled('status'), function ($builder) use ($request): void {
                $status = $request->string('status')->toString();

                if ($status === 'available') {
                    $builder->where('is_sold', false);
                }

                if ($status === 'sold') {
                    $builder->where('is_sold', true);
                }
            })
            ->when($request->boolean('featured_only'), fn ($builder) => $builder->where('is_featured', true));

        return Inertia::render('Admin/Cars/Index', [
            'cars' => $query->latest()->paginate(15)->withQueryString(),
            'filters' => $request->only(['search', 'make', 'model', 'status', 'featured_only']),
            'makeOptions' => Car::query()->orderBy('make')->distinct()->pluck('make')->values(),
            'modelOptions' => Car::query()->orderBy('model')->distinct()->pluck('model')->values(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Cars/Create', [
            'makeOptions' => Car::query()->orderBy('make')->distinct()->pluck('make')->values(),
            'modelOptions' => Car::query()->orderBy('model')->distinct()->pluck('model')->values(),
            'modelsByMake' => Car::query()
                ->select(['make', 'model'])
                ->orderBy('make')
                ->orderBy('model')
                ->get()
                ->groupBy('make')
                ->map(fn ($items) => $items->pluck('model')->unique()->values()),
        ]);
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

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Car created successfully.']);

        return to_route('admin.cars.index');
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
            'makeOptions' => Car::query()->orderBy('make')->distinct()->pluck('make')->values(),
            'modelOptions' => Car::query()->orderBy('model')->distinct()->pluck('model')->values(),
            'modelsByMake' => Car::query()
                ->select(['make', 'model'])
                ->orderBy('make')
                ->orderBy('model')
                ->get()
                ->groupBy('make')
                ->map(fn ($items) => $items->pluck('model')->unique()->values()),
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

        $currentImages = array_values((array) ($car->images ?? []));
        $existingImages = array_values((array) $request->input('existing_images', []));
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

        $removedImages = array_values(array_diff($currentImages, $validated['images']));

        foreach ($removedImages as $removedImage) {
            Storage::disk('public')->delete($removedImage);

            $directory = dirname($removedImage);

            if ($directory !== '.' && empty(Storage::disk('public')->files($directory))) {
                Storage::disk('public')->deleteDirectory($directory);
            }
        }

        $car->update($validated);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Car updated successfully.']);

        return to_route('admin.cars.index');
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

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Car deleted successfully.']);

        return back();
    }
}
