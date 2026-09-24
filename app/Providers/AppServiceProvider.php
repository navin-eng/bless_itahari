<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        require_once app_path('Helpers/DateHelper.php');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            try {
                $view->with('siteSettings', SiteSetting::current());
            } catch (\Throwable $e) {
                $view->with('siteSettings', new SiteSetting());
            }
        });

        // PERFORMANCE FIX: All home-section View Composers now use Cache::remember()
        // to avoid hitting the database on every page load for static/admin-managed content.
        // Each cache key is invalidated when an admin saves changes via the relevant controller.

        View::composer('frontend.pages.home.popup_notice', function ($view) {
            $view->with('popupNotice', Cache::remember('home.popup_notice', 600, function () {
                return \App\Models\Notice::where('show_in', 'p')->latest()->first();
            }));
        });

        View::composer('frontend.pages.home.hero_banner', function ($view) {
            $view->with('banners', Cache::remember('home.banners', 600, function () {
                return \App\Models\Banner::where('status', 1)->orderBy('sort_order', 'asc')->get();
            }));
        });

        View::composer('frontend.pages.home.notice_ticker', function ($view) {
            $view->with('marqueeNotice', Cache::remember('home.marquee_notice', 600, function () {
                return \App\Models\Notice::where('show_in', 'm')->latest()->first();
            }));
        });

        View::composer('frontend.pages.home.courses', function ($view) {
            $view->with('courses', Cache::remember('home.courses', 600, function () {
                return \App\Models\Course::where('status', 1)->get();
            }));
        });

        View::composer('frontend.pages.home.counter', function ($view) {
            $view->with('counter', Cache::remember('home.counter', 600, function () {
                return \App\Models\Counter::first();
            }));
        });

        View::composer('frontend.pages.home.official_messages', function ($view) {
            $view->with('messages', Cache::remember('home.messages', 600, function () {
                return \App\Models\CollegeMessage::where('status', 1)->orderBy('order')->get();
            }));
        });

        View::composer('frontend.pages.home.events', function ($view) {
            $view->with('events', Cache::remember('home.events', 600, function () {
                return \App\Models\Event::where('status', 1)->get();
            }));
        });

        View::composer('frontend.pages.home.testimonials', function ($view) {
            $view->with('testimonials', Cache::remember('home.testimonials', 600, function () {
                return \App\Models\Testimonial::orderBy('sort_order', 'asc')->get();
            }));
        });
    }
}
