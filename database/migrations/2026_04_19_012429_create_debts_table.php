<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('debts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('bill_session_id')->nullable()->constrained()->onDelete('set null');
        $table->enum('type', ['owe', 'lend']);
        $table->string('person_name');
        $table->string('description')->nullable();
        $table->decimal('amount', 15, 2);
        $table->decimal('paid_amount', 15, 2)->default(0);
        $table->enum('status', ['unpaid', 'partial', 'paid'])->default('unpaid');
        $table->date('due_date')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debts');
    }
};
