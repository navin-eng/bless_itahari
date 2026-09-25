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
        if (\Illuminate\Support\Facades\DB::getDriverName() === 'mysql') {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE events MODIFY image TEXT NULL;');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (\Illuminate\Support\Facades\DB::getDriverName() === 'mysql') {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE events MODIFY image TEXT NOT NULL;');
        }
    }
};
