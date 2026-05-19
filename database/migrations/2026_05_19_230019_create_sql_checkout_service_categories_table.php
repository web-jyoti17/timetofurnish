<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('checkout_service_categories')) {
            DB::statement(<<<'SQL'
CREATE TABLE `checkout_service_categories` (
  `id` int NOT NULL,
  `checkout_service_id` bigint UNSIGNED NOT NULL,
  `category_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('checkout_service_categories');
    }
};
