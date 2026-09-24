<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::table('site_settings')->updateOrInsert(
            ['id' => 1],
            [
                'site_name' => 'Shiksha Sandesh English School',
                'site_short_name' => 'SSES',
                'site_tagline' => 'Excellence in Education Since 1993',
                'contact_phone' => '021-546236',
                'contact_email' => 'info@shikshasandesh.edu.np',
                'contact_address' => 'Belbari-2, Lalbatti, Morang, Nepal',
                'whatsapp_number' => '9842065002',
                'facebook_url' => 'https://www.facebook.com/shikshasandesh',
                'header_button_text' => 'Enroll Now',
                'header_button_url' => '#',
                'student_portal_text' => 'Student Portal',
                'updated_at' => now(),
            ]
        );

        Cache::forget('site_settings.current');
    }

    public function down()
    {
        // No down needed
    }
};
