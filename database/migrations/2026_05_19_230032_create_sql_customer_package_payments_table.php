<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('customer_package_payments')) {
            DB::statement(<<<'SQL'
CREATE TABLE `customer_package_payments` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `customer_package_id` int NOT NULL,
  `payment_method` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `payment_details` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `approval` int NOT NULL,
  `offline_payment` int NOT NULL COMMENT '1=offline payment\r\n2=online paymnet',
  `reciept` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
SQL);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_package_payments');
    }
};
