<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->text('about_hero_title')->nullable()->after('about_intro');
            $table->text('about_hero_subtitle')->nullable()->after('about_hero_title');
            $table->string('about_badge_text')->nullable()->after('about_hero_subtitle');
            $table->string('about_story_title')->nullable()->after('about_badge_text');
            $table->text('about_story_body')->nullable()->after('about_story_title');
            
            // Stats
            $table->string('about_stat_1_number')->nullable()->after('about_story_body');
            $table->string('about_stat_1_label')->nullable()->after('about_stat_1_number');
            $table->string('about_stat_2_number')->nullable()->after('about_stat_1_label');
            $table->string('about_stat_2_label')->nullable()->after('about_stat_2_number');
            $table->string('about_stat_3_number')->nullable()->after('about_stat_2_label');
            $table->string('about_stat_3_label')->nullable()->after('about_stat_3_number');
            $table->string('about_stat_4_number')->nullable()->after('about_stat_3_label');
            $table->string('about_stat_4_label')->nullable()->after('about_stat_4_number');

            // Features & Amenities
            $table->text('about_features')->nullable()->after('about_stat_4_label');
            $table->text('about_amenities')->nullable()->after('about_features');

            // CTA Banner
            $table->string('about_cta_title')->nullable()->after('about_amenities');
            $table->text('about_cta_subtitle')->nullable()->after('about_cta_title');
            $table->string('about_cta_button_text')->nullable()->after('about_cta_subtitle');
            $table->string('about_cta_button_url')->nullable()->after('about_cta_button_text');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'about_hero_title',
                'about_hero_subtitle',
                'about_badge_text',
                'about_story_title',
                'about_story_body',
                'about_stat_1_number',
                'about_stat_1_label',
                'about_stat_2_number',
                'about_stat_2_label',
                'about_stat_3_number',
                'about_stat_3_label',
                'about_stat_4_number',
                'about_stat_4_label',
                'about_features',
                'about_amenities',
                'about_cta_title',
                'about_cta_subtitle',
                'about_cta_button_text',
                'about_cta_button_url',
            ]);
        });
    }
};
