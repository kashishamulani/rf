<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();

            // ✅ UUID for secure URLs
            $table->uuid('uuid')->unique();

            // Assignment details
            $table->string('assignment_name')->unique();
            $table->date('date');
            $table->date('deadline_date')->nullable();

            // Relations
            $table->foreignId('format_id')->constrained('formats')->onDelete('restrict');
            $table->foreignId('hr_id')->nullable()->constrained('hrs')->nullOnDelete();

            // Requirement and location
            $table->string('requirement')->nullable();
            $table->string('state')->nullable();
            $table->string('district')->nullable();
            $table->string('location')->nullable();
            $table->text('description')->nullable();

            // Store Manager Details
            $table->string('sm_name')->nullable();
            $table->string('sm_mobile', 15)->nullable();
            $table->string('sm_email')->nullable();

            // Batch and business info
            $table->string('batch_type')->nullable();
            $table->string('store_code')->nullable();
            $table->string('sourcing_machine')->nullable();
            $table->string('business')->nullable();
            $table->string('region')->nullable();
            $table->string('position_name')->nullable();
            $table->decimal('monthly_ctc', 10, 2)->nullable();
            $table->string('level')->nullable();
            $table->enum('ft_pt', ['FT','PT'])->nullable();
            $table->string('minimum_education_qualification')->nullable();
            $table->string('work_experience')->nullable();

            // Status and remarks
            $table->string('status')->default('Pending');
            $table->date('status_date')->nullable();
            $table->text('remark')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};