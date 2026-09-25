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
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('academic_level')->nullable()->after('name');
            $table->string('grade_span')->nullable()->after('academic_level');
            $table->string('evaluation_system')->nullable()->after('requirement');
            $table->longText('curriculum')->nullable()->after('fulldescription');
            $table->longText('rules')->nullable()->after('curriculum');
            $table->longText('admission_procedure')->nullable()->after('rules');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'academic_level',
                'grade_span',
                'evaluation_system',
                'curriculum',
                'rules',
                'admission_procedure'
            ]);
        });
    }
};
