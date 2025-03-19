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
        Schema::table('wedding_stories', function (Blueprint $table) {
            $table->unsignedInteger('order')->nullable()->after('access_code');
            $table->string('promo_link')->nullable()->after('youtube_link');
        });
    }
    
    public function down()
    {
        Schema::table('wedding_stories', function (Blueprint $table) {
            $table->dropColumn(['order', 'promo_link']);
        });
    }
};
