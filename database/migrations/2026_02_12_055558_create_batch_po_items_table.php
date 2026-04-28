<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
       public function up()
    {
        Schema::create('batch_po_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('batch_id')->constrained()->onDelete('cascade');
            $table->foreignId('po_item_id')->constrained('po_items')->onDelete('cascade');

            $table->integer('qty');

            $table->timestamps();
        });
    }



    public function down()
    {
        Schema::dropIfExists('batch_po_items');
    }
};
