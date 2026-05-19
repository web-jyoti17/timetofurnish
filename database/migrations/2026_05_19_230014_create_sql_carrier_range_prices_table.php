<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('carrier_range_prices')) {
            DB::statement(<<<'SQL'
CREATE TABLE `carrier_range_prices` (
  `id` int NOT NULL,
  `carrier_id` int NOT NULL,
  `carrier_range_id` int NOT NULL,
  `zone_id` int NOT NULL,
  `price` double(8,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
SQL);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('carrier_range_prices');
    }
};
