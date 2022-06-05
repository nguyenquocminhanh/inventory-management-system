<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // luu cac product trong invoice
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            // foreign key
            $table->integer('invoice_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('product_id')->nullable();
            
            $table->double('selling_qty')->nullable();
            $table->double('unit_price')->nullable();
            $table->double('selling_price')->nullable();
            $table->tinyInteger('status')->default('1')->comment('0=Pending, 1=Approved');

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
        Schema::dropIfExists('invoice_details');
    }
};
