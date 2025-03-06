<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->string('sala')->nullable()->default('')->change();
            $table->string('koscol')->nullable()->default('')->change();
            $table->integer('liczba_gosci')->nullable()->default(0)->change();
            $table->string('typ_wesela')->nullable()->default('')->change();
        });
    }

    public function down(): void
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->string('sala')->nullable(false)->change();
            $table->string('koscol')->nullable(false)->change();
            $table->integer('liczba_gosci')->nullable(false)->change();
            $table->string('typ_wesela')->nullable(false)->change();
        });
    }
};
