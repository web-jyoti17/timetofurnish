<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('offers')) {
            Schema::create('offers', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id')->nullable()->unsigned(); // NULL is admin, otherwise seller user id
                $table->string('name'); // Summer Offer, Hot Deal, etc.
                $table->string('badge_text')->nullable(); // 50% OFF, HOT DEAL
                $table->string('discount_type'); // percentage, fixed, badge_only
                $table->decimal('discount_value', 15, 2)->nullable();
                $table->text('custom_text')->nullable(); // Custom description/text about the offer
                $table->timestamp('starts_at')->nullable();
                $table->timestamp('ends_at')->nullable();
                $table->string('status')->default('pending'); // pending, approved, rejected, inactive
                $table->integer('priority')->default(0);
                $table->tinyInteger('show_on_home')->default(0);
                $table->timestamps();
                
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
