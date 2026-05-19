<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('sellers')) {
            DB::statement(<<<'SQL'
CREATE TABLE `sellers` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `rating` double(3,2) NOT NULL DEFAULT '0.00',
  `num_of_reviews` int NOT NULL DEFAULT '0',
  `num_of_sale` int NOT NULL DEFAULT '0',
  `verification_status` int NOT NULL DEFAULT '0',
  `verification_info` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `cash_on_delivery_status` int NOT NULL DEFAULT '0',
  `admin_to_pay` double(20,2) NOT NULL DEFAULT '0.00',
  `bank_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `bank_acc_name` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `bank_acc_no` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `bank_routing_no` int DEFAULT NULL,
  `bank_payment_status` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
SQL);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sellers');
    }
};
