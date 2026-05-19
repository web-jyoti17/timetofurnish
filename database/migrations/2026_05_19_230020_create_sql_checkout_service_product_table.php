<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('checkout_service_product')) {
            DB::statement(<<<'SQL'
CREATE TABLE `checkout_service_product` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `checkout_service_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
SQL);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('checkout_service_product');
    }
};
