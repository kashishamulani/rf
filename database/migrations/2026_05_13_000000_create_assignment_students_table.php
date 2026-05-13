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
        Schema::create('assignment_students', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->unsignedBigInteger('assignment_id');
            $table->unsignedBigInteger('mobilization_id');
            $table->unsignedBigInteger('progress_id')->nullable();
            
            // Samarth Fields
            $table->string('samarth_done')->nullable(); // pending, inprocess, done, not_done, failed
            $table->string('samarth_id')->nullable();
            $table->string('samarth_certificate')->nullable();
            
            // UAN Fields
            $table->boolean('uan_done')->default(0);
            $table->string('uan_number')->nullable();
            $table->string('uan_certificate')->nullable();
            
            // Offer Letter Fields
            $table->boolean('offer_letter_done')->default(0);
            $table->date('offer_letter_date')->nullable();
            $table->string('offer_letter_file')->nullable();
            
            // Registration Fields
            $table->string('registration_id')->nullable();
            $table->string('registration_password')->nullable();
            $table->string('registration_number')->nullable();
            
            // EC Fields
            $table->string('ec_number')->nullable();
            $table->date('ec_date')->nullable();
            
            // Placement Fields
            $table->date('date_of_placement')->nullable();
            $table->string('placement_company')->nullable();
            $table->string('placement_offering')->nullable();
            
            // Documents
            $table->boolean('documents_done')->default(0);
            
            // Remarks
            $table->text('remark')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Foreign Key Constraints
            $table->foreign('assignment_id')->references('id')->on('assignments')->onDelete('cascade');
            $table->foreign('mobilization_id')->references('id')->on('mobilizations')->onDelete('cascade');
            $table->foreign('progress_id')->references('id')->on('progresses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_students');
    }
};
