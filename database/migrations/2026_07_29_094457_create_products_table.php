<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {

            $table->id();

            $table->foreignId('category_id')
                ->constrained()
                ->cascadeOnDelete();

            // Informations générales
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->string('author')->nullable();
            $table->string('publisher')->nullable();
            $table->date('release_date')->nullable();

            // Type de produit
            $table->enum('type', [
                'book',
                'comic',
                'clothing',
            ]);

            // Prix
            $table->decimal('price', 10, 2);
            $table->decimal('old_price', 10, 2)->nullable();
            $table->float('weight')->nullable(); // si plus tard il faudrait calculer le frais de livraison

            // Stock
            $table->integer('stock')->default(0);

            // Référence
            $table->string('sku')->unique();

            // Image principale
            $table->string('cover_image')->nullable();

            // Produit mis en avant
            $table->boolean('is_featured')->default(false);

            // Publication
            $table->boolean('is_active')->default(true);
            $table->timestamp('published_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
