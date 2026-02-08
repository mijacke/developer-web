<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('frontend_colors_table', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('value', 7)->default('#6366F1');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        // Seed frontend colors (4 presets + 1 custom)
        \DB::table('frontend_colors_table')->insert([
            ['name' => 'Fialová', 'value' => '#6366F1', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Červená', 'value' => '#EF4444', 'is_active' => false, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Zelená', 'value' => '#10B981', 'is_active' => false, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Modrá', 'value' => '#3B82F6', 'is_active' => false, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Vlastná', 'value' => '#000000', 'is_active' => false, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('frontend_colors_table');
    }
};
