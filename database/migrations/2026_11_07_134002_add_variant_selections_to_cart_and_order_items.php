<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreignId('variant_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('product_option_id')->nullable()->constrained('options')->cascadeOnDelete();
            $table->foreignId('variant_option_id')->nullable()->constrained('options')->cascadeOnDelete();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('variant_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_option_id')->nullable()->constrained('options')->nullOnDelete();
            $table->foreignId('variant_option_id')->nullable()->constrained('options')->nullOnDelete();
            $table->string('variant_name')->nullable();
            $table->string('product_option_name')->nullable();
            $table->string('variant_option_name')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['variant_id']);
            $table->dropForeign(['product_option_id']);
            $table->dropForeign(['variant_option_id']);
            $table->dropColumn([
                'variant_id',
                'product_option_id',
                'variant_option_id',
                'variant_name',
                'product_option_name',
                'variant_option_name',
            ]);
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['variant_id']);
            $table->dropForeign(['product_option_id']);
            $table->dropForeign(['variant_option_id']);
            $table->dropColumn(['variant_id', 'product_option_id', 'variant_option_id']);
        });
    }
};
