<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('supplier_id')->default(0);
            $table->unsignedBigInteger('category_id');
            $table->text('description')->nullable();
            $table->decimal('amount', 15, 2);
            $table->decimal('exchange_rate', 15, 2)->default(1);
            $table->enum('type', ['formal', 'informal']);
            $table->dateTime('due_at');
            $table->timestamps();
        });

        Schema::table('expenses', function ($table) {
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete(null);
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete(null);
            $table->foreign('category_id')->references('id')->on('categories')->onDelete(null);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expanses');
    }
}
