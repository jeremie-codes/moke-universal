<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_types', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['digital', 'pickup', 'home_delivery'])->unique();
            $table->string('label');
            $table->timestamps();
        });

        $now = now();

        DB::table('delivery_types')->insert([
            ['type' => 'digital', 'label' => 'Électronique', 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'pickup', 'label' => 'Retrait en présentiel', 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'home_delivery', 'label' => 'Livraison à domicile', 'created_at' => $now, 'updated_at' => $now],
        ]);

        Schema::create('product_delivery_types', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('delivery_type_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->primary(['product_id', 'delivery_type_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_delivery_types');
        Schema::dropIfExists('delivery_types');
    }
};
