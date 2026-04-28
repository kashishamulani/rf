<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pos', function (Blueprint $table) {
            $table->id();
            $table->string('po_no')->unique(); // PO/WO Number
            $table->date('po_date');
            $table->date('period_from');      // new
            $table->date('period_to');        // new
            $table->decimal('gst', 8, 2)->nullable(); // new
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pos');
    }
};
