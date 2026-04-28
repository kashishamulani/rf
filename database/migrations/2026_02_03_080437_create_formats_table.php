<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formats', function (Blueprint $table) {
            $table->id();
            $table->string('type')->unique(); // ensures uniqueness at DB level
   // e.g., Billing / RIF
                // text content
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formats');
    }
};
