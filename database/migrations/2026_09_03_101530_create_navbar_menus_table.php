<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('navbar_menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url')->nullable();
            $table->string('type')->default('standard'); // 'standard' or 'course_dropdown'
            $table->integer('order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // Insert default menu items so the site doesn't break
        \Illuminate\Support\Facades\DB::table('navbar_menus')->insert([
            ['name' => 'Home', 'url' => '/', 'type' => 'standard', 'order' => 1, 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'About Us', 'url' => '/about-us', 'type' => 'standard', 'order' => 2, 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Academics', 'url' => null, 'type' => 'course_dropdown', 'order' => 3, 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Faculties', 'url' => '/member', 'type' => 'standard', 'order' => 4, 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Calendar', 'url' => '/calendar', 'type' => 'standard', 'order' => 5, 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Gallery', 'url' => '/gallery', 'type' => 'standard', 'order' => 6, 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Contact', 'url' => '/contact', 'type' => 'standard', 'order' => 7, 'status' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('navbar_menus');
    }
};
