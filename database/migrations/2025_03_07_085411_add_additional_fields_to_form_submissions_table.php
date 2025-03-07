<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->date('wedding_date')->nullable()->after('last_name');
            $table->string('wedding_location')->nullable()->after('wedding_date');
            $table->string('marriage_location')->nullable()->after('wedding_location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->dropColumn(['wedding_date', 'wedding_location', 'marriage_location']);
        });
    }
};
