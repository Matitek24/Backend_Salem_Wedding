<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('weddings', function (Blueprint $table) {
            $table->id();
            $table->string('bride_name'); // Imię Panny Młodej
            $table->string('groom_name'); // Imię Pana Młodego
            $table->date('date')->unique(); // Data wesela, musi być unikalna
            $table->enum('wedding_type', ['boho', 'klasyczny', 'plenerowy'])->default('klasyczny'); // Typ ślubu
            $table->string('venue'); // Miejsce wesela
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weddings');
    }
};
