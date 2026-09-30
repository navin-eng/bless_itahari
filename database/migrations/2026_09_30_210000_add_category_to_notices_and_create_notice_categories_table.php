<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create notice_categories table
        if (!Schema::hasTable('notice_categories')) {
            Schema::create('notice_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('slug')->nullable();
                $table->timestamps();
            });

            // Seed default categories
            $defaults = [
                'General & Administration',
                'Academic & Examinations',
                'Admissions',
                'Events & Programs',
                'Sports & Activities',
                'Circular & News',
            ];

            foreach ($defaults as $name) {
                DB::table('notice_categories')->insert([
                    'name' => $name,
                    'slug' => \Illuminate\Support\Str::slug($name),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 2. Add category to notices table
        if (Schema::hasTable('notices') && !Schema::hasColumn('notices', 'category')) {
            Schema::table('notices', function (Blueprint $table) {
                $table->string('category')->nullable()->after('title');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('notices') && Schema::hasColumn('notices', 'category')) {
            Schema::table('notices', function (Blueprint $table) {
                $table->dropColumn('category');
            });
        }

        Schema::dropIfExists('notice_categories');
    }
};
