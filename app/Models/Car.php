<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Car extends Model
{
    /** @use HasFactory<\Database\Factories\CarFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'make',
        'model',
        'year',
        'price',
        'mileage',
        'fuel_type',
        'transmission',
        'body_type',
        'color',
        'condition',
        'description',
        'features',
        'images',
        'is_featured',
        'is_sold',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'features' => 'array',
            'images' => 'array',
            'is_featured' => 'boolean',
            'is_sold' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Car $car): void {
            if ($car->title === null || $car->title === '') {
                return;
            }

            if ($car->isDirty('title') || $car->slug === null || $car->slug === '') {
                $baseSlug = Str::slug($car->title);
                $slug = $baseSlug;
                $suffix = 1;

                while (
                    static::query()
                        ->where('slug', $slug)
                        ->when($car->exists, fn (Builder $query) => $query->whereKeyNot($car->getKey()))
                        ->exists()
                ) {
                    $slug = $baseSlug.'-'.$suffix;
                    $suffix++;
                }

                $car->slug = $slug;
            }
        });
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('is_sold', false);
    }

    protected function primaryImage(): Attribute
    {
        return Attribute::make(
            get: fn (): string => (is_array($this->images) && ($this->images[0] ?? null) !== null)
                ? (string) $this->images[0]
                : 'images/car-placeholder.svg',
        );
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
