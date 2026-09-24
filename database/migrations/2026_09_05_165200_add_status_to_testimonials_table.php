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
        if (Schema::hasTable('testimonials') && !Schema::hasColumn('testimonials', 'status')) {
            Schema::table('testimonials', function (Blueprint $table) {
                $table->tinyInteger('status')->default(1)->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('testimonials') && Schema::hasColumn('testimonials', 'status')) {
            Schema::table('testimonials', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
