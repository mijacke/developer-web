<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('localities', function (Blueprint $table) {
            $table->id();
            $table->string('dm_id')->nullable()->unique();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('type')->nullable();
            $table->string('status')->default('available');
            $table->string('status_label')->nullable();
            $table->string('status_color')->nullable();
            $table->decimal('area', 10, 2)->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->decimal('rent', 10, 2)->nullable();
            $table->string('floor')->nullable();
            $table->string('image')->nullable();
            $table->text('svg_path')->nullable();
            $table->json('regions')->nullable();
            $table->json('metadata')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('localities');
    }
};
