<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
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

        foreach ($payload as $key => $value) {
            if (! is_string($key)) {
                continue;
            }

            SiteSetting::set($key, is_bool($value) ? ($value ? '1' : '0') : (string) $value);
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
