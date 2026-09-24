<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('about_layout')->default('classic')->after('navbar_sticky');
            $table->string('about_hero_image')->nullable()->after('about_layout');
            $table->string('about_school_image')->nullable()->after('about_hero_image');
            $table->string('about_principal_image')->nullable()->after('about_school_image');
            $table->string('about_principal_name')->nullable()->after('about_principal_image');
            $table->string('about_principal_designation')->nullable()->after('about_principal_name');
            $table->text('about_principal_message')->nullable()->after('about_principal_designation');
            $table->text('about_mission')->nullable()->after('about_principal_message');
            $table->text('about_vision')->nullable()->after('about_mission');
            $table->string('about_established_year')->nullable()->after('about_vision');
            $table->string('about_affiliation')->nullable()->after('about_established_year');
            $table->text('about_intro')->nullable()->after('about_affiliation');
            $table->text('about_values')->nullable()->after('about_intro');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
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
            ]);
        });
    }
};
