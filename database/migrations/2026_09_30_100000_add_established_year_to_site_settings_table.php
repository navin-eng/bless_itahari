<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('site_settings', 'established_year')) {
            Schema::table('site_settings', function (Blueprint $table) {
                $table->string('established_year', 100)->nullable()->after('site_tagline');
            });

            // Initialize from existing about_established_year or default
            $existing = DB::table('site_settings')->first();
            if ($existing) {
                $val = $existing->about_established_year ?: '2050 B.S. (1993 A.D.)';
                DB::table('site_settings')->where('id', $existing->id)->update([
                    'established_year' => $val,
                    'about_established_year' => $existing->about_established_year ?: $val,
                ]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('site_settings', 'established_year')) {
            Schema::table('site_settings', function (Blueprint $table) {
                $table->dropColumn('established_year');
            });
        }
    }
};
