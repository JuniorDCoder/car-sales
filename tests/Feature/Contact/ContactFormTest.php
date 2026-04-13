<?php

use App\Mail\ContactMail;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

test('contact form sends enquiry email', function () {
    Mail::fake();

    SiteSetting::query()->create([
        'key' => 'contact_email',
        'value' => 'dealer@example.com',
        'type' => 'email',
        'label' => 'Contact Email Address',
        'group' => 'contact',
    ]);

    $response = $this->post(route('contact.store'), [
        'name' => 'Alex Driver',
        'email' => 'alex@example.com',
        'phone' => '123456789',
        'message' => 'I am interested in your premium cars inventory.',
    ]);

    $response->assertSessionHas('success');

    Mail::assertSent(ContactMail::class);
});

