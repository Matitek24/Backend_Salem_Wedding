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
        Schema::table('form_submissions', function (Blueprint $table) {
            // Usuwamy niepotrzebne kolumny
            $table->dropColumn(['last_name', 'wedding_location', 'marriage_location']);

            // Dodajemy nową kolumnę 'pakiet'
            $table->text('pakiet')->nullable()->after('wedding_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_submissions', function (Blueprint $table) {
            // Przywracamy usunięte kolumny
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('wedding_location')->nullable()->after('last_name');
            $table->string('marriage_location')->nullable()->after('wedding_location');

            // Usuwamy kolumnę 'pakiet'
            $table->dropColumn('pakiet');
        });
    }
};
