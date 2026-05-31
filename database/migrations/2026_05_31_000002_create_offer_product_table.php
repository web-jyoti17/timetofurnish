<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('offer_product')) {
            Schema::create('offer_product', function (Blueprint $table) {
                $table->unsignedBigInteger('offer_id');
                $table->integer('product_id');
                
                $table->foreign('offer_id')->references('id')->on('offers')->onDelete('cascade');
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
                
                $table->primary(['offer_id', 'product_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('offer_product');
    }
};
