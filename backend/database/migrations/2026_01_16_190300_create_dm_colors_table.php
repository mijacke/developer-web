<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('colors_table', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('label');
            $table->string('value', 7)->default('#FFFFFF');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Seed default map colors
        \DB::table('colors_table')->insert([
            ['name' => 'Farba tlačidiel', 'label' => 'Farba tlačidiel', 'value' => '#6366F1', 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Farba nadpisov', 'label' => 'Farba nadpisov', 'value' => '#1E293B', 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Farba obsahových textov', 'label' => 'Farba obsahových textov', 'value' => '#475569', 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('colors_table');
    }
};
