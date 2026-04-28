<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();

            // ✅ Phase foreign key
            $table->foreignId('phase_id')
                  ->nullable()
                  ->constrained('phases')
                  ->cascadeOnDelete();

            $table->string('name');     
             $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('activities');
    }
};
