<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateWeddingsTableAddSkadNasZnalazliscieColumn extends Migration
{
    public function up()
    {
        Schema::table('weddings', function (Blueprint $table) {
            // Dodajemy kolumnę 'skad_nas_znalazliscie' typu string, która może być null
            $table->string('skad_nas_znalazliscie')->nullable()->after('social_link');
        });
    }

    public function down()
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->dropColumn('skad_nas_znalazliscie');
        });
    }
}
