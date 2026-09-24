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
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->boolean('admissions_open')->default(false)->after('contact_email');
            $table->string('admission_title')->nullable()->after('admissions_open');
            $table->text('admission_description')->nullable()->after('admission_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['admissions_open', 'admission_title', 'admission_description']);
        });
    }
};
