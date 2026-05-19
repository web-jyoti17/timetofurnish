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
        if (Schema::hasTable('checkout_service_product')) {
            return;
        }

        Schema::create('checkout_service_product', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->unsignedBigInteger('checkout_service_id');
            $table->timestamps();

            $table->index('product_id');
            $table->index('checkout_service_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checkout_service_product');
    }
};
