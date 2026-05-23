<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('product_addon_options') && !Schema::hasColumn('product_addon_options', 'quantity')) {
            Schema::table('product_addon_options', function (Blueprint $table) {
                $table->unsignedInteger('quantity')->default(0)->after('price');
            });
        }

        if (Schema::hasTable('product_addon_options_global') && !Schema::hasColumn('product_addon_options_global', 'quantity')) {
            Schema::table('product_addon_options_global', function (Blueprint $table) {
                $table->unsignedInteger('quantity')->default(0)->after('price');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('product_addon_options') && Schema::hasColumn('product_addon_options', 'quantity')) {
            Schema::table('product_addon_options', function (Blueprint $table) {
                $table->dropColumn('quantity');
            });
        }

        if (Schema::hasTable('product_addon_options_global') && Schema::hasColumn('product_addon_options_global', 'quantity')) {
            Schema::table('product_addon_options_global', function (Blueprint $table) {
                $table->dropColumn('quantity');
            });
        }
    }
};
