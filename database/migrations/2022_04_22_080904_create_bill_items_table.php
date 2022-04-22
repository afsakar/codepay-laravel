<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bill_id');
            $table->unsignedBigInteger('material_id');
            $table->decimal('price', 10, 2);
            $table->decimal('quantity', 10, 2);
            $table->integer('created_by')->default(0);
            $table->timestamps();
        });

        Schema::table('bill_items', function ($table) {
            $table->foreign('bill_id')->references('id')->on('bills')->onDelete('cascade');
            $table->foreign('material_id')->references('id')->on('materials')->onDelete(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bill_items');
    }
}
