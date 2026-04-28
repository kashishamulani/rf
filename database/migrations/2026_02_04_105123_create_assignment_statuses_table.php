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
    Schema::create('assignment_statuses', function (Blueprint $table) {
        $table->id();

        $table->foreignId('assignment_id')
              ->constrained('assignments')
              ->onDelete('cascade');

        $table->string('status');
        $table->date('status_date')->nullable();
        $table->text('remark')->nullable();

        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('assignment_statuses');
}

    /**
     * Reverse the migrations.
     */

};
