<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('combined_orders')) {
            DB::statement(<<<'SQL'
CREATE TABLE `combined_orders` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `shipping_address` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `grand_total` double(20,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
SQL);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('combined_orders');
    }
};
