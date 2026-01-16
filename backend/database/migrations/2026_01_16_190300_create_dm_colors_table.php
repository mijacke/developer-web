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
            ['name' => 'buttons', 'label' => 'Farba tlačidiel', 'value' => '#6366F1', 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'headings', 'label' => 'Farba nadpisov', 'value' => '#1E293B', 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'content_text', 'label' => 'Farba obsahových textov', 'value' => '#475569', 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('dm_colors');
    }
};
