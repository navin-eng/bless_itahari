<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_name',
        'site_short_name',
        'site_tagline',
        'site_logo',
        'site_favicon',
        'primary_color',
        'primary_dark',
        'primary_light',
        'accent_color',
        'contact_phone',
        'contact_email',
        'contact_address',
        'google_map_iframe',
        'whatsapp_number',
        'facebook_url',
        'youtube_url',
        'instagram_url',
        'gallery_layout',
        'header_button_text',
        'header_button_url',
        'student_portal_text',
        'student_portal_url',
        'show_sticky_notice',
        'sticky_notice_title',
        'sticky_notice_limit',
        'show_topbar',
        'show_whatsapp_button',
        'show_back_to_top',
        'sticky_notice_desktop_collapsed',
        'sticky_notice_mobile_collapsed',
        'calendar_format',
        'admissions_open',
        'admission_title',
        'admission_description',
        'enable_analytics',
        'google_analytics_id',
        'microsoft_clarity_id',
        'analytics_property_id',
        'navbar_layout',
        'navbar_theme',
        'navbar_sticky',
        'about_layout',
        'about_hero_image',
        'about_school_image',
        'about_principal_image',
        'about_principal_name',
        'about_principal_designation',
        'about_principal_message',
        'about_mission',
        'about_vision',
        'about_established_year',
        'about_affiliation',
        'about_intro',
        'about_values',
    ];

    public static function current()
    {
        try {
            return Cache::rememberForever('site_settings.current', function () {
                $setting = static::first();
                if ($setting) {
                    if (!empty($setting->site_favicon) && !file_exists(public_path($setting->site_favicon))) {
                        $setting->site_favicon = null;
                    }
                    if (!empty($setting->site_logo) && !file_exists(public_path($setting->site_logo))) {
                        $setting->site_logo = null;
                    }
                    return $setting;
                }
                return static::make([
                    'site_name' => 'Shiksha Sandesh English School',
                    'site_short_name' => 'SSES',
                    'site_tagline' => 'Excellence in Education Since 1993',
                    'site_logo' => null,
                    'site_favicon' => null,
                    'primary_color' => '#1a4d8c',
                    'primary_dark' => '#0e2d54',
                    'primary_light' => '#2e74c9',
                    'accent_color' => '#f59e0b',
                    'contact_phone' => '021-546236',
                    'contact_email' => 'info@shikshasandesh.edu.np',
                    'contact_address' => 'Belbari-2, Lalbatti, Morang, Nepal',
                    'whatsapp_number' => '9842065002',
                    'facebook_url' => 'https://www.facebook.com/shikshasandesh',
                    'youtube_url' => '#',
                    'instagram_url' => '#',
                    'gallery_layout' => 'masonry',
                    'header_button_text' => 'Enroll Now',
                    'header_button_url' => '#',
                    'student_portal_text' => 'Student Portal',
                    'student_portal_url' => '#',
                    'show_sticky_notice' => true,
                    'sticky_notice_title' => 'Latest Notices',
                    'sticky_notice_limit' => 5,
                    'show_topbar' => true,
                    'show_whatsapp_button' => true,
                    'show_back_to_top' => true,
                    'sticky_notice_desktop_collapsed' => false,
                    'sticky_notice_mobile_collapsed' => true,
                    'calendar_format' => 'ad',
                    'admissions_open' => false,
                    'admission_title' => 'Admissions are Closed',
                    'admission_description' => 'Please check back later or contact us directly if you have any urgent inquiries.',
                ]);
            });
        } catch (\Throwable $e) {
            return static::make([
                'site_name' => 'Shiksha Sandesh English School',
                'site_short_name' => 'SSES',
                'site_tagline' => 'Excellence in Education Since 1993',
                'primary_color' => '#1a4d8c',
                'primary_dark' => '#0e2d54',
                'primary_light' => '#2e74c9',
                'accent_color' => '#f59e0b',
                'contact_phone' => '021-546236',
                'contact_email' => 'info@shikshasandesh.edu.np',
                'contact_address' => 'Belbari-2, Lalbatti, Morang, Nepal',
                'whatsapp_number' => '9842065002',
                'show_sticky_notice' => true,
                'sticky_notice_limit' => 5,
                'show_topbar' => true,
                'show_whatsapp_button' => true,
                'show_back_to_top' => true,
                'sticky_notice_desktop_collapsed' => false,
                'sticky_notice_mobile_collapsed' => true,
                'calendar_format' => 'ad',
                'admissions_open' => false,
            ]);
        }
    }
}
