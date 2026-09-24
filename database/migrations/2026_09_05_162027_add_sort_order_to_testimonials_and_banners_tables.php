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
        Schema::table('testimonials', function (Blueprint $table) {
            $table->integer('sort_order')->default(0)->after('role');
        });

        Schema::table('banners', function (Blueprint $table) {
            $table->integer('sort_order')->default(0)->after('title2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });

        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
