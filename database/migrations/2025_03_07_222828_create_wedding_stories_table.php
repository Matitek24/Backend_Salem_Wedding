<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wedding_stories', function (Blueprint $table) {
            $table->id();
            $table->string('couple_names'); // Imiona pary
            $table->text('description'); // Opis wesela
            $table->string('thumbnail'); // Ścieżka do zdjęcia
            $table->string('youtube_link')->nullable(); // Link do filmu na YouTube
            $table->string('gallery_link')->nullable(); // Link do galerii
            $table->string('access_code'); // Kod dostępu
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wedding_stories');
    }
};
