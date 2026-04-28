<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
       Schema::create('po_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('po_id')->constrained('pos')->onDelete('cascade');
    $table->string('item');
    $table->decimal('value', 12, 2);
    $table->integer('quantity');
    $table->integer('used_quantity')->default(0);
    $table->timestamps();
});

    }

    public function down()
    {
        Schema::dropIfExists('po_items');
    }
};
    