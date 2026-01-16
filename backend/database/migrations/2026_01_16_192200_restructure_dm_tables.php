<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Rename dm_colors to dm_map_colors
        Schema::rename('dm_colors', 'dm_map_colors');
        
        // Rename dm_settings to dm_fonts
        Schema::rename('dm_settings', 'dm_fonts');
        
        // Create dm_frontend_colors table
        Schema::create('dm_frontend_colors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('label');
            $table->string('value', 7)->default('#6366F1');
            $table->boolean('is_default')->default(false);
            $table->boolean('is_custom')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Seed frontend colors
        \DB::table('dm_frontend_colors')->insert([
            ['name' => 'Fialová', 'label' => 'Fialová (predvolená)', 'value' => '#6366F1', 'is_default' => true, 'is_custom' => false, 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Červená', 'label' => 'Červená', 'value' => '#EF4444', 'is_default' => false, 'is_custom' => false, 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Zelená', 'label' => 'Zelená', 'value' => '#10B981', 'is_default' => false, 'is_custom' => false, 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Modrá', 'label' => 'Modrá', 'value' => '#3B82F6', 'is_default' => false, 'is_custom' => false, 'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Vlastná', 'label' => 'Vlastná farba', 'value' => '#6366F1', 'is_default' => false, 'is_custom' => true, 'sort_order' => 5, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Update dm_fonts - keep only font data
        \DB::table('dm_fonts')->where('key', 'accent_color')->delete();
        
        // Add more fonts
        \DB::table('dm_fonts')->insertOrIgnore([
            ['key' => 'Aboreto', 'value' => 'Aboreto, Georgia, serif', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'SF Pro', 'value' => '-apple-system, BlinkMacSystemFont, sans-serif', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'Inter', 'value' => 'Inter, sans-serif', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('dm_frontend_colors');
        Schema::rename('dm_map_colors', 'dm_colors');
        Schema::rename('dm_fonts', 'dm_settings');
    }
};
