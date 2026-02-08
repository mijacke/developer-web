<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('statuses_table', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('label');
            $table->string('color', 7)->default('#6B7280');
            $table->boolean('is_default')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Seed default statuses
        \DB::table('statuses_table')->insert([
            ['name' => 'Voľné', 'label' => 'Voľné', 'color' => '#49CD40', 'is_default' => true, 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Rezervované', 'label' => 'Rezervované', 'color' => '#F59E0B', 'is_default' => false, 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Predané', 'label' => 'Predané', 'color' => '#EF4444', 'is_default' => false, 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pripravujeme', 'label' => 'Pripravujeme', 'color' => '#8B5CF6', 'is_default' => false, 'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('statuses_table');
    }
};
