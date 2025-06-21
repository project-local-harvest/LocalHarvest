<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('shop_serial_number')->unique();
            $table->string('shop_name');
            $table->string('contact_number')->nullable();
            $table->text('address')->nullable();
            $table->string('owner_picture_url')->nullable();
            $table->timestamps();
        });

    }


    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
