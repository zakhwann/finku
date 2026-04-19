<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('bill_sessions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('title');
        $table->string('place')->nullable();
        $table->date('date');
        $table->decimal('tax_percent', 5, 2)->default(0);
        $table->decimal('discount_amount', 15, 2)->default(0);
        $table->enum('split_mode', ['equal', 'custom'])->default('equal');
        $table->enum('status', ['draft', 'calculated', 'saved'])->default('draft');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_sessions');
    }
};
