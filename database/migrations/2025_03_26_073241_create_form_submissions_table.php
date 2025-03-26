<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormSubmissionsTable extends Migration
{
    public function up()
    {
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->string('miejscowosc')->nullable()->after('pakiet');
        });
    }

    public function down()
    {
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->dropColumn('miejscowosc');
        });
    }
}
