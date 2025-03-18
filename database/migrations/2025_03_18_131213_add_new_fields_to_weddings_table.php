<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToWeddingsTable extends Migration
{
    public function up()
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->string('telefon_panny')->after('liczba_gosci')->nullable();
            $table->string('telefon_pana')->after('telefon_panny')->nullable();
            $table->string('pakiet')->after('telefon_pana')->nullable();
            $table->string('typ_zamowienia')->after('pakiet')->default('rezerwacja');
        });
    }

    public function down()
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->dropColumn(['telefon_panny', 'telefon_pana', 'pakiet', 'typ_zamowienia']);
        });
    }
}
