<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('activity_assignment_statuses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('activity_assignment_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->enum('status', ['open', 'close', 'cancel', 'pending'])
                  ->default('pending');

            $table->text('remark')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('activity_assignment_statuses');
    }
};
