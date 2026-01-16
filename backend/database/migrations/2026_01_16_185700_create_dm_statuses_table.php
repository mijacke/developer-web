<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dm_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('label');
            $table->string('color', 7)->default('#6B7280');
            $table->boolean('is_default')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Seed default statuses
        \DB::table('dm_statuses')->insert([
            ['name' => 'available', 'label' => 'Voľný', 'color' => '#49CD40', 'is_default' => true, 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'reserved', 'label' => 'Rezervovaný', 'color' => '#F59E0B', 'is_default' => false, 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'sold', 'label' => 'Predaný', 'color' => '#EF4444', 'is_default' => false, 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'preparing', 'label' => 'Pripravujeme', 'color' => '#8B5CF6', 'is_default' => false, 'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('dm_statuses');
    }
};
