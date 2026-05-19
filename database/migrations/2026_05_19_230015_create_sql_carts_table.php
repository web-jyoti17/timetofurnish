<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('carts')) {
            DB::statement(<<<'SQL'
CREATE TABLE `carts` (
  `id` int UNSIGNED NOT NULL,
  `owner_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `temp_user_id` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `address_id` int NOT NULL DEFAULT '0',
  `product_id` int DEFAULT NULL,
  `variation` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `price` double(20,2) DEFAULT '0.00',
  `tax` double(20,2) DEFAULT '0.00',
  `shipping_cost` double(20,2) NOT NULL DEFAULT '0.00',
  `shipping_type` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `pickup_point` int DEFAULT NULL,
  `carrier_id` int DEFAULT NULL,
  `discount` double(10,2) NOT NULL DEFAULT '0.00',
  `product_referral_code` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `coupon_code` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `coupon_applied` tinyint NOT NULL DEFAULT '0',
  `quantity` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `addons` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `services` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `addon_price` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
SQL);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
