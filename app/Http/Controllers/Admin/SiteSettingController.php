<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SiteSettingController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Settings', [
            'settings' => SiteSetting::query()->orderBy('group')->orderBy('key')->get()->groupBy('group'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $payload = (array) $request->input('settings', []);
        $definitions = SiteSetting::query()->get()->keyBy('key');

        $rules = ['settings' => ['array']];
        $attributes = [];

        foreach (array_keys($payload) as $key) {
            if (! is_string($key) || ! $definitions->has($key)) {
                continue;
            }

            $setting = $definitions->get($key);

            $rules["settings.{$key}"] = match ($setting?->type) {
                'email' => ['nullable', 'email'],
                'url' => ['nullable', 'url'],
                'phone' => ['nullable', 'string', 'max:30'],
                'textarea' => ['nullable', 'string', 'max:5000'],
                'boolean' => ['boolean'],
                default => ['nullable', 'string', 'max:255'],
            };

            $attributes["settings.{$key}"] = $setting?->label ?? $key;
        }

        $request->validate($rules, attributes: $attributes);

        foreach ($payload as $key => $value) {
            if (! is_string($key)) {
                continue;
            }

            SiteSetting::set($key, is_bool($value) ? ($value ? '1' : '0') : (string) $value);
        }

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Settings updated successfully.']);

        return back();
    }
}
