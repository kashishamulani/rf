<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignment_forms', function (Blueprint $table) {
            $table->id();

            // 🔑 Relationship
            $table->unsignedBigInteger('assignment_id');

            // 📄 Form details (from API)
            $table->unsignedBigInteger('form_id')->nullable();
            $table->string('form_name')->nullable();
            $table->string('location')->nullable();
            $table->string('status')->nullable();
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->text('link')->nullable();

            $table->timestamps();

            // 🔗 Foreign key
            $table->foreign('assignment_id')
                ->references('id')
                ->on('assignments')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignment_forms');
    }
};
