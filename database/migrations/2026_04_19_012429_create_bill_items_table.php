<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
{
    Schema::create('bill_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('bill_session_id')->constrained()->onDelete('cascade');
        $table->foreignId('bill_member_id')->constrained()->onDelete('cascade');
        $table->string('name');
        $table->decimal('price', 15, 2);
        $table->integer('qty')->default(1);
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_items');
    }
};
