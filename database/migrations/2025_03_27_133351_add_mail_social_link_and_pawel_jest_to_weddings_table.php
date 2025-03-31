<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMailSocialLinkAndPawelJestToWeddingsTable extends Migration
{
    public function up()
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->string('mail')->nullable()->after('photo');
            $table->string('social_link')->nullable()->after('mail');
            $table->boolean('pawel_jest')->default(false)->after('social_link');
        });
    }

    public function down()
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->dropColumn(['mail', 'social_link', 'pawel_jest']);
        });
    }
}
