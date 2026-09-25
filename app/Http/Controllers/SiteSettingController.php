<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class SiteSettingController extends Controller
{
    public function edit()
    {
        $settings = SiteSetting::current();
        $logs = ActivityLog::where('module', 'site_settings')->latest()->take(10)->get();

        return view('backend.pages.site_settings.edit', compact('settings', 'logs'));
    }

    public function update(Request $request)
    {
        // Automatically run migrations to ensure production database is up to date
        try {
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Auto-migration failed: ' . $e->getMessage());
        }

        $settings = SiteSetting::first();

        $data = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_short_name' => 'required|string|max:100',
            'site_tagline' => 'required|string|max:255',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'site_favicon' => 'nullable|mimes:ico,png,jpg,svg,webp|max:1024',
            'primary_color' => 'required|string|max:20',
            'primary_dark' => 'required|string|max:20',
            'primary_light' => 'required|string|max:20',
            'accent_color' => 'required|string|max:20',
            'contact_phone' => 'nullable|string|max:100',
            'contact_email' => 'nullable|email|max:255',
            'contact_address' => 'nullable|string|max:255',
            'google_map_iframe' => 'nullable|string',
            'whatsapp_number' => 'nullable|string|max:50',
            'facebook_url' => 'nullable|string|max:255',
            'youtube_url' => 'nullable|string|max:255',
            'instagram_url' => 'nullable|string|max:255',
            'gallery_layout' => 'required|in:masonry,spotlight,storyboard',
            'header_button_text' => 'nullable|string|max:80',
            'header_button_url' => 'nullable|string|max:255',
            'student_portal_text' => 'nullable|string|max:80',
            'student_portal_url' => 'nullable|string|max:255',
            'show_sticky_notice' => 'nullable|boolean',
            'sticky_notice_title' => 'nullable|string|max:80',
            'sticky_notice_limit' => 'nullable|integer|min:1|max:10',
            'show_topbar' => 'nullable|boolean',
            'show_whatsapp_button' => 'nullable|boolean',
            'show_back_to_top' => 'nullable|boolean',
            'sticky_notice_desktop_collapsed' => 'nullable|boolean',
            'sticky_notice_mobile_collapsed' => 'nullable|boolean',
            'calendar_format' => 'required|in:ad,bs',
            'admissions_open' => 'nullable|boolean',
            'admission_title' => 'nullable|string|max:255',
            'admission_description' => 'nullable|string',
            'enable_analytics' => 'nullable|boolean',
            'google_analytics_id' => 'nullable|string|max:100',
            'microsoft_clarity_id' => 'nullable|string|max:100',
            'analytics_property_id' => 'nullable|string|max:100',
            'navbar_layout' => 'nullable|string|in:default,centered,right',
            'navbar_theme' => 'nullable|string|in:light,dark,primary',
            'navbar_sticky' => 'nullable|boolean',
        ]);

        $data['enable_analytics'] = $request->boolean('enable_analytics');
        $data['navbar_sticky'] = $request->boolean('navbar_sticky');

        $settings = SiteSetting::first();

        // Handle Site Logo Upload
        if ($request->hasFile('site_logo')) {
            $logo = $request->file('site_logo');
            $ext = $logo->getClientOriginalExtension();
            $logoName = 'logo_' . time() . '_' . Str::random(8) . '.' . $ext;
            $destination = public_path('backend/images/settings');
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }
            $logo->move($destination, $logoName);
            $data['site_logo'] = 'backend/images/settings/' . $logoName;

            if ($settings && $settings->site_logo && file_exists(public_path($settings->site_logo))) {
                @unlink(public_path($settings->site_logo));
            }
        } elseif ($request->boolean('remove_logo')) {
            if ($settings && $settings->site_logo && file_exists(public_path($settings->site_logo))) {
                @unlink(public_path($settings->site_logo));
            }
            $data['site_logo'] = null;
        } else {
            unset($data['site_logo']);
        }

        // Handle Site Favicon Upload
        if ($request->hasFile('site_favicon')) {
            $favicon = $request->file('site_favicon');
            $ext = $favicon->getClientOriginalExtension();
            $favName = 'favicon_' . time() . '_' . Str::random(8) . '.' . $ext;
            $destination = public_path('backend/images/settings');
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }
            $favicon->move($destination, $favName);
            $data['site_favicon'] = 'backend/images/settings/' . $favName;

            if ($settings && $settings->site_favicon && file_exists(public_path($settings->site_favicon))) {
                @unlink(public_path($settings->site_favicon));
            }
        } elseif ($request->boolean('remove_favicon')) {
            if ($settings && $settings->site_favicon && file_exists(public_path($settings->site_favicon))) {
                @unlink(public_path($settings->site_favicon));
            }
            $data['site_favicon'] = null;
        } else {
            unset($data['site_favicon']);
        }

        $data['show_sticky_notice'] = $request->boolean('show_sticky_notice');
        $data['show_topbar'] = $request->boolean('show_topbar');
        $data['show_whatsapp_button'] = $request->boolean('show_whatsapp_button');
        $data['show_back_to_top'] = $request->boolean('show_back_to_top');
        $data['sticky_notice_desktop_collapsed'] = $request->boolean('sticky_notice_desktop_collapsed');
        $data['sticky_notice_mobile_collapsed'] = $request->boolean('sticky_notice_mobile_collapsed');
        $data['admissions_open'] = $request->boolean('admissions_open');
        $data['navbar_sticky'] = $request->boolean('navbar_sticky');

        $original = $settings ? $settings->toArray() : [];

        if ($settings) {
            $settings->update($data);
        } else {
            SiteSetting::create($data);
        }

        Cache::forget('site_settings.current');

        $changedKeys = collect($data)
            ->filter(function ($value, $key) use ($original) {
                if (is_array($value) || is_object($value)) {
                    return true;
                }
                return !array_key_exists($key, $original) || (string) $original[$key] !== (string) $value;
            })
            ->keys()
            ->implode(', ');

        ActivityLog::create([
            'module' => 'site_settings',
            'action' => 'updated',
            'user_name' => Auth::user()->name ?? 'System',
            'summary' => $changedKeys ? 'Updated: ' . $changedKeys : 'Site settings updated',
        ]);

        Alert::success('Updated', 'Site settings updated successfully');

        return back();
    }
}
