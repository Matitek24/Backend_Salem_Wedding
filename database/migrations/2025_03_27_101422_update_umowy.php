<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('umowy', function (Blueprint $table) {
            // Sprawdzenie, czy kolumny istnieją przed usunięciem
            if (Schema::hasColumn('umowy', 'nip')) {
                $table->dropColumn('nip');
            }
            if (Schema::hasColumn('umowy', 'telefon_mlodej')) {
                $table->dropColumn('telefon_mlodej');
            }

            // Dodanie nowych kolumn
            $table->string('pakiet')->after('nr_dowodu');
            $table->date('data')->nullable()->after('koscol');
            $table->date('data_final')->nullable()->after('data');
            $table->decimal('stawka', 10, 2)->after('data_final');
            $table->decimal('zadatek', 10, 2)->after('stawka');
            $table->boolean('dron')->default(false)->after('zadatek');
        });
    }

    public function down(): void
    {
        Schema::table('umowy', function (Blueprint $table) {
            // Przywracanie usuniętych kolumn
            $table->string('nip')->nullable();
            $table->string('telefon_mlodej')->nullable();

            // Usunięcie nowych kolumn, ale w bezpieczny sposób
            $columnsToDrop = ['pakiet', 'data', 'data_final', 'stawka', 'zadatek', 'dron'];
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('umowy', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
