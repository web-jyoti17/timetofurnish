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
        Schema::create('checkout_services', function (Blueprint $table) {

            $table->id();

            // Service Name
            $table->string('name');

            // delivery / assembly / installation etc
            $table->string('type');

            // Fixed amount
            $table->decimal('price', 10, 2)->default(0);

            // Optional description
            $table->text('description')->nullable();

            // Show on frontend
            $table->boolean('status')->default(1);

            // Optional sorting
            $table->integer('sort_order')->default(0);

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
        Schema::dropIfExists('checkout_services');
    }
};
