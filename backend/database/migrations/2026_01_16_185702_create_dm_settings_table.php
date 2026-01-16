<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dm_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('value')->nullable();
            $table->timestamps();
        });

        // Seed default settings
        \DB::table('dm_settings')->insert([
            ['key' => 'accent_color', 'value' => '#6366F1', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'font_family', 'value' => 'Inter', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('dm_settings');
    }
};
