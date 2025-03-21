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
        Schema::table('weddings', function (Blueprint $table) {
            $table->text('uwagi')->nullable(); // Dodajemy pole po 'data'
        });
    }
    
    public function down()
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->dropColumn('uwagi');
        });
    }
    
};
