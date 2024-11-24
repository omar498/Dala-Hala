<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('consumer_products', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('consumer_id');
            $table->unsignedBigInteger('product_id');
            $table->timestamps();
            $table->softDeletes(); // Add soft deletes

            

            $table->foreign('consumer_id')->references('id')->on('consumers')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumer_products');
    }
};
