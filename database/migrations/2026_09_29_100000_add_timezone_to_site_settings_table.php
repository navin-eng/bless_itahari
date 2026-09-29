<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('site_settings', 'timezone')) {
            Schema::table('site_settings', function (Blueprint $table) {
                $table->string('timezone', 100)->default('Asia/Kathmandu')->after('calendar_format');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('site_settings', 'timezone')) {
            Schema::table('site_settings', function (Blueprint $table) {
                $table->dropColumn('timezone');
            });
        }
    }
};
