<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('developer_map_settings');
    }

    public function down(): void
    {
        // Not restoring - was replaced by structured tables
    }
};
