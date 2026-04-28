<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mobilizations', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('email')->unique();
            $table->string('mobile')->unique();

            $table->string('highest_qualification')->nullable();
            $table->date('dob')->nullable();
            $table->integer('age')->nullable();
            $table->string('gender')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('location')->nullable();

            $table->json('relocation')->nullable();
            $table->json('languages')->nullable();

            $table->decimal('current_salary', 12, 2)->nullable();
            $table->decimal('preferred_salary', 12, 2)->nullable();

            $table->foreignId('role_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('sub_role_id')->nullable()->constrained('subroles')->nullOnDelete();

            // Documents
            $table->string('aadhar')->nullable();
            $table->string('pan_card')->nullable();
            $table->string('marksheets')->nullable();
            $table->string('driving_license')->nullable();
            $table->string('experience_letter')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mobilizations');
    }
};
