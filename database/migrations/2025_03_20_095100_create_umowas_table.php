<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('umowy', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wedding_id')->constrained()->onDelete('cascade');
            $table->string('imie');
            $table->string('nazwisko');
            $table->string('pesel', 11)->nullable();
            $table->string('nr_dowodu')->nullable();
            $table->text('adres')->nullable();
            $table->string('nip', 10)->nullable();
            $table->string('telefon_mlodego')->nullable();
            $table->string('telefon_mlodej')->nullable();
            $table->string('plik_umowy')->nullable();
            $table->date('data_podpisania')->nullable();
            $table->enum('status', ['utworzona', 'podpisana', 'anulowana'])->default('utworzona');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('umowy');
    }
};