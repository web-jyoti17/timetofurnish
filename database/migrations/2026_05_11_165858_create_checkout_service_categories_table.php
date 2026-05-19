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
        if (Schema::hasTable('checkout_service_categories')) {
            return;
        }

        Schema::create('checkout_service_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('checkout_service_id');
            $table->integer('category_id');
            $table->timestamps();

            $table->index('checkout_service_id');
            $table->index('category_id');
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
