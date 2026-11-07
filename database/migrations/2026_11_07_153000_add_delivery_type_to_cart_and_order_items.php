<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreignId('delivery_type_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('delivery_type_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('delivery_type_label')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['delivery_type_id']);
            $table->dropColumn(['delivery_type_id', 'delivery_type_label']);
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['delivery_type_id']);
            $table->dropColumn('delivery_type_id');
        });
    }
};
