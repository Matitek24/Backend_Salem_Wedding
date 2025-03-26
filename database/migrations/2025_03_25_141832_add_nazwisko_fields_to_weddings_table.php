<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNazwiskoFieldsToWeddingsTable extends Migration
{
    public function up()
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->string('nazwisko1')->nullable()->after('imie1');
            $table->string('nazwisko2')->nullable()->after('imie2');
        });
    }

    public function down()
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->dropColumn(['nazwisko1', 'nazwisko2']);
        });
    }
}
