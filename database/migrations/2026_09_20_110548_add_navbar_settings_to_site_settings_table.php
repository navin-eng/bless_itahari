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
            $table->string('navbar_layout')->default('default')->after('site_favicon'); // default, centered, right
            $table->string('navbar_theme')->default('light')->after('navbar_layout'); // light, dark, primary
            $table->boolean('navbar_sticky')->default(true)->after('navbar_theme');
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
            $table->dropColumn(['navbar_layout', 'navbar_theme', 'navbar_sticky']);
        });
    }
};
