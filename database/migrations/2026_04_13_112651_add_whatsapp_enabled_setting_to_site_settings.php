<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('site_settings')->updateOrInsert(
            ['key' => 'whatsapp_enabled'],
            [
                'value' => '1',
                'type' => 'boolean',
                'label' => 'Enable WhatsApp Buttons',
                'group' => 'contact',
                'updated_at' => now(),
                'created_at' => now(),
            ],
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('site_settings')->where('key', 'whatsapp_enabled')->delete();
    }
};
