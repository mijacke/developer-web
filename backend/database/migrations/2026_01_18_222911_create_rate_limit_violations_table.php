<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rate_limit_violations', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45);
            $table->string('route');
            $table->string('method', 10);
            $table->integer('attempts')->default(1);
            $table->timestamps();

            $table->index(['ip_address', 'created_at']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rate_limit_violations');
    }
};
