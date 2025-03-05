<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('weddings', function (Blueprint $table) {
            // Usunięcie nullable() i dodanie unikalności
            $table->date('date')->unique()->nullable(false)->change();
        });
    }

    public function down()
    {
        Schema::table('weddings', function (Blueprint $table) {
            // Przywrócenie nullable(), jeśli trzeba cofnąć migrację
            $table->date('date')->nullable()->change();
        });
    }
};
