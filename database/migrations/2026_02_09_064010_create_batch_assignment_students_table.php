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
        Schema::create('batch_assignment_students', function (Blueprint $table) {
            $table->id();

            $table->foreignId('batch_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->unsignedBigInteger('assignment_id');

            $table->foreign('assignment_id')
                  ->references('id')
                  ->on('assignments')
                  ->restrictOnDelete();

            $table->string('student_id'); // from core API

            $table->timestamps();

            $table->unique(
                ['batch_id','assignment_id','student_id'],
                'batch_assign_student_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_assignment_students');
    }
};