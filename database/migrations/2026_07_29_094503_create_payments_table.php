<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {

            $table->id();

            $table->foreignId('order_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('reference')->unique();

            $table->string('provider');
            $table->string('phone')->nullable();

            $table->string('method');

            $table->decimal('amount',10,2);

            $table->string('currency',3)->default('USD');

            $table->enum('status',[
                'pending',
                'success',
                'failed'
            ])->default('pending');

            $table->string('transaction_id')->nullable();

            $table->json('response')->nullable();

            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
