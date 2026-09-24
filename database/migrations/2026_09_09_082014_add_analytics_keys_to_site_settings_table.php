<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('google_analytics_id')->nullable()->after('contact_address');
            $table->string('microsoft_clarity_id')->nullable()->after('google_analytics_id');
            $table->string('analytics_property_id')->nullable()->after('microsoft_clarity_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['google_analytics_id', 'microsoft_clarity_id', 'analytics_property_id']);
        });
    }
};
