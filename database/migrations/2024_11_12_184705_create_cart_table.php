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
        Schema::create('cart', function (Blueprint $table) {
            $table->id();
            // Primary key
                       $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
                       $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); 
                       $table->integer('quantity')->default(1);
                       $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart');
    }
};
