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
        Schema::create('checkout_service_categories', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('checkout_service_id');

            $table->unsignedBigInteger('category_id');

            $table->timestamps();

            $table->foreign('checkout_service_id')
                ->references('id')
                ->on('checkout_services')
                ->onDelete('cascade');

            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checkout_service_categories');
    }
};
