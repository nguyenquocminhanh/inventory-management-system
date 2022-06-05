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
        // luu qua khu tra due cua customer
        Schema::create('payment_details', function (Blueprint $table) {
            $table->id();
            // foreign key
            $table->integer('invoice_id')->nullable();

            // lan pay gan nhat
            $table->double('current_paid_amount')->nullable();
            $table->date('date')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('payment_details');
    }
};
