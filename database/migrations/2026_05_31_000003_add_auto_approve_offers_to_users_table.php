<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'auto_approve_offers')) {
            Schema::table('users', function (Blueprint $table) {
                $table->tinyInteger('auto_approve_offers')->default(0)->after('email');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'auto_approve_offers')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('auto_approve_offers');
            });
        }
    }
};
