<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->nullable();
            $table->string('owner')->nullable();
            $table->string('tel_number')->nullable();
            $table->string('gsm_number')->nullable();
            $table->string('fax_number')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('tax_office')->nullable();
            $table->string('tax_number')->nullable()->unique();
            $table->enum('status', ['active', 'inactive'])->default('active')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
}
