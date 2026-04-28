<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->id();

            // ✅ Basic Batch Info
            $table->string('batch_code')->unique();

            // ✅ Location Fields (NEW)
            $table->string('state')->nullable();
            $table->string('district')->nullable();
        
            $table->text('address')->nullable();

            // ✅ Number Allotted
            $table->integer('number_allotted')->nullable();

            // ✅ Status
            $table->string('status')->default('Open');

            // ✅ Training Dates
            $table->date('training_from')->nullable();
            $table->date('training_to')->nullable();

            // ✅ Training Hours
            $table->decimal('training_hours', 8, 2)->nullable();

         

            // ✅ PO Foreign Key
            $table->foreignId('po_id')->nullable()->constrained('pos')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};
