<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Drop foreign keys if they exist
        try {
            DB::statement('ALTER TABLE product_stock_attributes DROP FOREIGN KEY psa_product_id_foreign');
        } catch (\Exception $e) {}

        try {
            DB::statement('ALTER TABLE product_stock_attributes DROP FOREIGN KEY psa_product_stock_id_foreign');
        } catch (\Exception $e) {}

        // 2. Alter columns to match the parent table's 'int' type (INT, signed) and be NULLABLE
        DB::statement('ALTER TABLE product_stock_attributes MODIFY product_id INT NULL');
        DB::statement('ALTER TABLE product_stock_attributes MODIFY product_stock_id INT NULL');

        // 3. Recreate foreign keys
        DB::statement('ALTER TABLE product_stock_attributes ADD CONSTRAINT psa_product_id_foreign FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE product_stock_attributes ADD CONSTRAINT psa_product_stock_id_foreign FOREIGN KEY (product_stock_id) REFERENCES product_stocks(id) ON DELETE CASCADE');

        // 4. Add user_id and category_id columns if they don't exist yet
        if (!Schema::hasColumn('product_stock_attributes', 'user_id')) {
            DB::statement('ALTER TABLE product_stock_attributes ADD COLUMN user_id INT NULL');
            DB::statement('ALTER TABLE product_stock_attributes ADD CONSTRAINT psa_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL');
        }

        if (!Schema::hasColumn('product_stock_attributes', 'category_id')) {
            DB::statement('ALTER TABLE product_stock_attributes ADD COLUMN category_id INT NULL');
            DB::statement('ALTER TABLE product_stock_attributes ADD CONSTRAINT psa_category_id_foreign FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL');
        }
    }

    public function down(): void
    {
        try {
            DB::statement('ALTER TABLE product_stock_attributes DROP FOREIGN KEY psa_user_id_foreign');
            DB::statement('ALTER TABLE product_stock_attributes DROP COLUMN user_id');
        } catch (\Exception $e) {}

        try {
            DB::statement('ALTER TABLE product_stock_attributes DROP FOREIGN KEY psa_category_id_foreign');
            DB::statement('ALTER TABLE product_stock_attributes DROP COLUMN category_id');
        } catch (\Exception $e) {}
    }
};
