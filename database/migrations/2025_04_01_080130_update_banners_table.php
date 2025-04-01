<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Banner;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->integer('sort_order')->default(0)->after('page');
        });

        // Initialize existing records with increasing sort_order per page
        $pages = Banner::select('page')->distinct()->pluck('page');
        
        foreach ($pages as $page) {
            $order = 1;
            $banners = Banner::where('page', $page)->get();
            
            foreach ($banners as $banner) {
                $banner->update(['sort_order' => $order]);
                $order++;
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};