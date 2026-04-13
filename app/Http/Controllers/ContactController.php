<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:40',
            'message' => 'required|string|min:10|max:3000',
        ]);

        $configuredEmail = trim((string) SiteSetting::get('contact_email', ''));
        $fallbackEmail = (string) config('mail.from.address', 'info@example.com');
        $to = filter_var($configuredEmail, FILTER_VALIDATE_EMAIL) ? $configuredEmail : $fallbackEmail;

        Mail::to($to)->send(new ContactMail($data));

        return back()->with('success', 'Thanks! Your message has been sent successfully.');
    }
}
