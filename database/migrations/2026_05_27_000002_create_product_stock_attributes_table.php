<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Drop the variant_attributes column from product_stocks table if it exists
        if (Schema::hasTable('product_stocks') && Schema::hasColumn('product_stocks', 'variant_attributes')) {
            Schema::table('product_stocks', function (Blueprint $table) {
                $table->dropColumn('variant_attributes');
            });
        }

        // 2. Create the product_stock_attributes table
        if (!Schema::hasTable('product_stock_attributes')) {
            Schema::create('product_stock_attributes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
                $table->foreignId('product_stock_id')->constrained('product_stocks')->onDelete('cascade');
                $table->foreignId('attribute_id')->nullable()->constrained('attributes')->onDelete('cascade');
                $table->string('attribute_name');
                $table->string('attribute_value');
                $table->integer('attribute_sort_order')->default(0);
                $table->integer('value_sort_order')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // 1. Drop the product_stock_attributes table
        Schema::dropIfExists('product_stock_attributes');

        // 2. Restore the variant_attributes column to product_stocks table
        if (Schema::hasTable('product_stocks') && !Schema::hasColumn('product_stocks', 'variant_attributes')) {
            Schema::table('product_stocks', function (Blueprint $table) {
                $table->json('variant_attributes')->nullable()->after('variant');
            });
        }
    }
};
