<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('corporation_id')->default(0);
            $table->unsignedBigInteger('withholding_id')->default(0);
            $table->date('issue_date');
            $table->text('notes')->nullable();
            $table->string('invoice_number')->unique();
            $table->enum('status', ['draft','paid','cancelled'])->default('draft');
            $table->integer('created_by')->default(0);
            $table->timestamps();
        });

        Schema::table('invoices', function ($table) {
            $table->foreign('company_id')->references('id')->on('companies')->onDelete(null);
            $table->foreign('corporation_id')->references('id')->on('corporations')->onDelete(null);
            $table->foreign('withholding_id')->references('id')->on('with_holdings')->onDelete(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
