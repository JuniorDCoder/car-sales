<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = ['key', 'value', 'type', 'label', 'group'];

    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::query()->where('key', $key)->value('value');

        return $setting ?? $default;
    }

    public static function set(string $key, string $value): void
    {
        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value],
        );
    }
}
