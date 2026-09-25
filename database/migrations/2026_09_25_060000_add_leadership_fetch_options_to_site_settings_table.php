<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('site_settings', 'about_leadership_source')) {
                $table->string('about_leadership_source')->default('all')->nullable()->after('about_principal_message');
            }
            if (!Schema::hasColumn('site_settings', 'about_selected_leadership_ids')) {
                $table->text('about_selected_leadership_ids')->nullable()->after('about_leadership_source');
            }
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            if (Schema::hasColumn('site_settings', 'about_selected_leadership_ids')) {
                $table->dropColumn('about_selected_leadership_ids');
            }
            if (Schema::hasColumn('site_settings', 'about_leadership_source')) {
                $table->dropColumn('about_leadership_source');
            }
        });
    }
};
