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
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nazwa pary (np. "Aleksandra & Łukasz")
            $table->text('content'); // Treść opinii
            $table->string('image')->nullable(); // Ścieżka do zdjęcia
            $table->enum('image_position', ['left', 'right'])->default('left'); // Pozycja zdjęcia
            $table->boolean('is_featured')->default(false); // Czy wyróżniona opinia
            $table->integer('order')->default(0); // Kolejność wyświetlania
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};