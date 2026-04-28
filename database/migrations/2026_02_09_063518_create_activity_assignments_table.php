<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('activity_assignments', function (Blueprint $table) {
            $table->id();

            // ✅ Team Member FK
            $table->foreignId('team_member_id')
                ->constrained('team_members')
                ->cascadeOnDelete();

            // ✅ Assignment FK
            $table->foreignId('assignment_id')
                ->constrained('assignments')
                ->cascadeOnDelete();

            // ✅ Phase FK
            $table->foreignId('phase_id')
                ->constrained('phases')
                ->cascadeOnDelete();

            // ✅ Activity FK
            $table->foreignId('activity_id')
                ->constrained('activities')
                ->cascadeOnDelete();

            // ✅ Dates
            $table->dateTime('assigned_at');
            $table->dateTime('target_at');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('activity_assignments');
    }
};
