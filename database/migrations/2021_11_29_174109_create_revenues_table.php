<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revenues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('customer_id')->default(0);
            $table->unsignedBigInteger('category_id');
            $table->text('description')->nullable();
            $table->decimal('amount', 15, 2);
            $table->decimal('exchange_rate', 15, 2)->default(1);
            $table->enum('type', ['formal', 'informal']);
            $table->dateTime('due_at');
            $table->timestamps();
        });

        Schema::table('revenues', function ($table) {
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete(null);
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete(null);
            $table->foreign('category_id')->references('id')->on('categories')->onDelete(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('revenues');
    }
}
