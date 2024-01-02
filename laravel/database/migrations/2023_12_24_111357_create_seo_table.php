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
        Schema::create('seo', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedInteger('seoble_id');
            $table->string('seoble_type');
            $table->index(['seoble_type', 'seoble_id']);
            $table->text('title')->nullable();
            $table->text('description')->nullable();
            $table->text('meta-title')->nullable();
            $table->text('meta-description')->nullable();
            $table->text('meta-keywords')->nullable();
            $table->string('og-type')->nullable();
            $table->string('og-image')->nullable();
            $table->string('og-url')->nullable();
            $table->string('og-site_name')->nullable();
            $table->string('og-locale')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo');
    }
};
