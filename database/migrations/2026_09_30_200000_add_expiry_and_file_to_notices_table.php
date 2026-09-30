<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notices', function (Blueprint $table) {
            if (!Schema::hasColumn('notices', 'file')) {
                $table->string('file')->nullable()->after('image');
            }
            if (!Schema::hasColumn('notices', 'file_name')) {
                $table->string('file_name')->nullable()->after('file');
            }
            if (!Schema::hasColumn('notices', 'file_size')) {
                $table->string('file_size', 50)->nullable()->after('file_name');
            }
            if (!Schema::hasColumn('notices', 'expires_at')) {
                $table->dateTime('expires_at')->nullable()->after('show_in');
            }
            if (!Schema::hasColumn('notices', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('expires_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('notices', function (Blueprint $table) {
            $columnsToDrop = [];
            foreach (['file', 'file_name', 'file_size', 'expires_at', 'is_active'] as $col) {
                if (Schema::hasColumn('notices', $col)) {
                    $columnsToDrop[] = $col;
                }
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
