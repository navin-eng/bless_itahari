<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::table('site_settings')->where('id', 1)->update([
            'primary_color' => '#1a4d8c',
            'primary_dark'  => '#0e2d54',
            'primary_light' => '#2e74c9',
            'accent_color'  => '#f59e0b',
            'updated_at'    => now(),
        ]);

        Cache::forget('site_settings.current');
    }

    public function down()
    {
        // No revert needed
    }
};
