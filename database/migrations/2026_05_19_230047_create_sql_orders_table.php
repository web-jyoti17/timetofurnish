<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('orders')) {
            DB::statement(<<<'SQL'
CREATE TABLE `orders` (
  `id` int NOT NULL,
  `combined_order_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `guest_id` int DEFAULT NULL,
  `seller_id` int DEFAULT NULL,
  `shipping_address` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `additional_info` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `shipping_type` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `order_from` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'web',
  `pickup_point_id` int NOT NULL DEFAULT '0',
  `carrier_id` int DEFAULT NULL,
  `delivery_status` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT 'pending',
  `payment_type` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `payment_status` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT 'unpaid',
  `payment_details` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `grand_total` double(20,2) DEFAULT NULL,
  `igst` tinyint(1) DEFAULT '0',
  `coupon_discount` double(20,2) NOT NULL DEFAULT '0.00',
  `code` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `tracking_code` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `shiprocket_order_id` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `date` int NOT NULL,
  `viewed` int NOT NULL DEFAULT '0',
  `delivery_viewed` int NOT NULL DEFAULT '1',
  `payment_status_viewed` int DEFAULT '1',
  `commission_calculated` int NOT NULL DEFAULT '0',
  `manual_payment` tinyint(1) DEFAULT NULL,
  `manual_payment_data` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
SQL);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
