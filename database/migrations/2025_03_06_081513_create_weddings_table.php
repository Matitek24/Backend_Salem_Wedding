<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('weddings', function (Blueprint $table) {
            $table->id();
            $table->string('imie1');            // Imię pierwsze
            $table->string('imie2');            // Imię drugie
            $table->date('data');               // Data wesela
            $table->enum('typ_wesela', ['boho', 'klasyczny', 'plenerowy'])
                  ->default('klasyczny');       // Typ wesela
            $table->string('sala');             // Sala weselna
            $table->string('koscol');           // Kościół
            $table->integer('liczba_gosci');    // Liczba gości
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weddings');
    }
};
