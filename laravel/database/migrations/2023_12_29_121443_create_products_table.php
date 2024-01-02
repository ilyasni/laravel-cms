<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('slug')->unique();
            $table->decimal('price', 10, 2)->default(0);
            $table->boolean('is_enabled')->default(0);
        });

        if (Schema::hasTable('categories')) {
            Schema::create('category_product', function (Blueprint $table) {
                $table->string('sort_order')->default(0);
                $table->boolean('main')->default(0);
                $table->foreignId('product_id')
                    ->constrained()
                    ->onDelete('cascade');
                $table->foreignId('category_id')
                    ->constrained()
                    ->onDelete('cascade');

                $table->primary(['product_id', 'category_id']);
            });
        }
        Schema::create('product_models', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('product_model_product', function (Blueprint $table) {
            $table->foreignId('product_model_id')
                ->constrained('product_models')
                ->onDelete('cascade');
            $table->foreignId('product_id')
                ->constrained('products')
                ->onDelete('cascade');

            $table->primary(['product_id', 'product_model_id']);
        });
        Schema::create('product_positions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 10, 2)->default(0);
            $table->foreignId('product_id')
                ->constrained()
                ->onDelete('cascade');
            $table->string('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_positions');
        Schema::dropIfExists('model_product');
        Schema::dropIfExists('models');
        Schema::dropIfExists('category_product');
        Schema::dropIfExists('products');
    }
};
