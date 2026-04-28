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
    Schema::create('assignment_batch', function (Blueprint $table) {
        $table->id();

        $table->foreignId('assignment_id')
            ->constrained()
            ->onDelete('cascade');

        $table->foreignId('batch_id')
            ->constrained()
            ->onDelete('cascade');

        $table->unique(['assignment_id', 'batch_id']); // avoid duplicates
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_batch');
    }
};
