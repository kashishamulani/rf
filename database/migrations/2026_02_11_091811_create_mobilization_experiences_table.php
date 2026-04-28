<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('mobilization_experiences', function (Blueprint $table) {
            $table->id();

            $table->foreignId('mobilization_id')->constrained()->cascadeOnDelete();

            $table->string('organization')->nullable();
            $table->string('designation')->nullable();
            $table->string('duration')->nullable();

            $table->foreignId('role_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('sub_role_id')->nullable()->constrained('subroles')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mobilization_experiences');
    }
};
