<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('dm_id')->nullable()->unique();
            $table->foreignId('parent_id')->nullable()->constrained('projects')->onDelete('cascade');
            $table->string('name');
            $table->string('type')->nullable();
            $table->string('image')->nullable();
            $table->string('map_key')->unique();
            $table->integer('sort_order')->default(0);
            $table->json('regions')->nullable();
            $table->json('frontend')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
