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
        Schema::create('weddings', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique(); // Unikalna data
            $table->integer('reservation_count')->default(1); // Ile wesel danego dnia (domyślnie 1)
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weddings');
    }
};
