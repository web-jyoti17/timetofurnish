<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('product_addon_global_category')) {
            Schema::create('product_addon_global_category', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('product_addon_global_id');
                $table->unsignedInteger('category_id');
                $table->timestamps();

                $table->foreign('product_addon_global_id', 'addon_global_fk')
                      ->references('id')
                      ->on('product_addons_global')
                      ->onDelete('cascade');

                $table->foreign('category_id', 'addon_category_fk')
                      ->references('id')
                      ->on('categories')
                      ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('product_addon_global_category');
    }
};
