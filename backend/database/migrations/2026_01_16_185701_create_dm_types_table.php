<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dm_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('label');
            $table->string('color', 7)->default('#405ECD');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Seed default types
        \DB::table('dm_types')->insert([
            ['name' => 'Bytovka', 'label' => 'Bytovka', 'color' => '#405ECD', 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dom', 'label' => 'Dom', 'color' => '#9333EA', 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pozemok', 'label' => 'Pozemok', 'color' => '#14B8A6', 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Parkovanie', 'label' => 'Parkovanie', 'color' => '#8B5CF6', 'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Komercia', 'label' => 'Komercia', 'color' => '#EC4899', 'sort_order' => 5, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('dm_types');
    }
};
