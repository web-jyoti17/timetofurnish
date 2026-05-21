<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('order_invoices')) {
            Schema::create('order_invoices', function (Blueprint $table) {
                $table->id();
                $table->integer('order_id')->index();
                $table->string('copy_type', 32);
                $table->string('invoice_number');
                $table->string('invoice_name');
                $table->string('file_path');
                $table->timestamp('generated_at')->nullable();
                $table->timestamps();

                $table->unique(['order_id', 'copy_type']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('order_invoices');
    }
};
