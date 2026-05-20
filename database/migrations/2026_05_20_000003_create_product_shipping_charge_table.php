<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('product_shipping_charge')) {
            return;
        }

        Schema::create('product_shipping_charge', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('shipping_charge_id');
            $table->timestamps();

            $table->index('product_id');
            $table->index('shipping_charge_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('shipping_charge_id')->references('id')->on('shipping_charges')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_shipping_charge');
    }
};
