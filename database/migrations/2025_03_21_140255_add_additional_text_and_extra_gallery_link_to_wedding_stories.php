<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wedding_stories', function (Blueprint $table) {
            $table->text('additional_text')->nullable()->after('description');
            $table->string('extra_gallery_link')->nullable()->after('gallery_link');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wedding_stories', function (Blueprint $table) {
            $table->dropColumn(['additional_text', 'extra_gallery_link']);
        });
    }
};
