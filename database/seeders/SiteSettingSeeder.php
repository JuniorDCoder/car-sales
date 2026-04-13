<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'contact_email', 'value' => 'info@autoelite.com', 'type' => 'email', 'label' => 'Contact Email Address', 'group' => 'contact'],
            ['key' => 'whatsapp_number', 'value' => '+1XXXXXXXXXX', 'type' => 'phone', 'label' => 'WhatsApp Number (with country code, e.g. +1XXXXXXXXXX)', 'group' => 'contact'],
            ['key' => 'whatsapp_message', 'value' => "Hello, I'm interested in one of your cars.", 'type' => 'textarea', 'label' => 'Default WhatsApp Greeting Message', 'group' => 'contact'],
            ['key' => 'site_name', 'value' => 'AutoNest', 'type' => 'text', 'label' => 'Site Name', 'group' => 'branding'],
            ['key' => 'site_tagline', 'value' => 'Drive Your Dream', 'type' => 'text', 'label' => 'Site Tagline', 'group' => 'branding'],
            ['key' => 'site_logo_url', 'value' => '/images/autoelite-logo.svg', 'type' => 'url', 'label' => 'Site Logo URL', 'group' => 'branding'],
            ['key' => 'address', 'value' => '123 Motor Mile Drive, Los Angeles, CA 90001', 'type' => 'textarea', 'label' => 'Physical Address', 'group' => 'contact'],
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/autoelite', 'type' => 'url', 'label' => 'Facebook Page URL', 'group' => 'social'],
            ['key' => 'instagram_url', 'value' => 'https://instagram.com/autoelite', 'type' => 'url', 'label' => 'Instagram Profile URL', 'group' => 'social'],
            ['key' => 'hero_headline', 'value' => 'Premium Cars. Premium Experience.', 'type' => 'text', 'label' => 'Homepage Hero Headline', 'group' => 'branding'],
            ['key' => 'hero_subheadline', 'value' => 'Discover elite vehicles with trusted support from first click to test drive.', 'type' => 'text', 'label' => 'Homepage Hero Subheadline', 'group' => 'branding'],
            ['key' => 'currency_symbol', 'value' => '$', 'type' => 'text', 'label' => 'Currency Symbol (e.g. $, USD, EUR)', 'group' => 'general'],
            ['key' => 'currency_position', 'value' => 'before', 'type' => 'text', 'label' => 'Currency Position (before or after)', 'group' => 'general'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::query()->updateOrCreate(
                ['key' => $setting['key']],
                $setting,
            );
        }
    }
}
