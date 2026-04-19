<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('wishlists', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('name');
        $table->text('description')->nullable();
        $table->decimal('target_price', 15, 2);
        $table->decimal('saved_amount', 15, 2)->default(0);
        $table->string('image_url')->nullable();
        $table->string('product_url')->nullable();
        $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
        $table->enum('status', ['saving', 'ready', 'purchased'])->default('saving');
        $table->date('target_date')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};
