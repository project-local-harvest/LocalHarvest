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
        Schema::create('shop_inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->foreignId('fertilizer_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('stock_quantity');
            $table->enum('stock_status', ['in_stock', 'low_stock', 'out_of_stock']);
            $table->decimal('price_per_unit', 8, 2)->nullable();
            $table->timestamps();

            $table->unique(['shop_id', 'fertilizer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_inventories');
    }
};
