<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('shipping_charge_categories')) {
            return;
        }

        Schema::create('shipping_charge_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shipping_charge_id');
            $table->integer('category_id');
            $table->timestamps();

            $table->index('shipping_charge_id');
            $table->index('category_id');
            $table->foreign('shipping_charge_id')->references('id')->on('shipping_charges')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('shipping_charge_categories');
    }
};
