<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku');
            $table->decimal('price', 20)->default(0);
            $table->integer('quantity')->default(1);
            $table->unsignedBigInteger('tax_id')->default(0);
            $table->unsignedBigInteger('material_category_id')->default(0);
            $table->unsignedBigInteger('unit_id')->default(0);
            $table->unsignedBigInteger('currency_id')->default(1);
            $table->enum('type', ['service', 'procurement', 'service_procurement'])->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active')->nullable();
            $table->integer('created_by')->default(0);
            $table->timestamps();
        });

        Schema::table('materials', function ($table) {
            $table->foreign('unit_id')->references('id')->on('units')->onDelete(null);
            $table->foreign('tax_id')->references('id')->on('taxes')->onDelete(null);
            $table->foreign('material_category_id')->references('id')->on('material_categories')->onDelete(null);
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('materials');
    }
}
