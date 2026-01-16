<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dm_colors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('label');
            $table->string('value', 7)->default('#FFFFFF');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Seed default map colors
        \DB::table('dm_colors')->insert([
            ['name' => 'background', 'label' => 'Pozadie mapy', 'value' => '#F8FAFC', 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'border', 'label' => 'Farba okrajov', 'value' => '#E2E8F0', 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'text', 'label' => 'Farba textu', 'value' => '#1E293B', 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'highlight', 'label' => 'Zvýraznenie', 'value' => '#6366F1', 'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('dm_colors');
    }
};
