<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Expand the `url` column in the galleries table from varchar(255) to TEXT.
 * Facebook CDN URLs can exceed 1000+ characters, so TEXT is required.
 *
 * Uses raw DB::statement() to avoid needing Doctrine/DBAL for ->change().
 * Compatible with both MySQL (production) and SQLite (local dev).
 */
return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE galleries MODIFY COLUMN url TEXT NULL');
        }
        // SQLite: varchar has no enforced length limit, no action needed.
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            // Truncate first to avoid data loss errors on rollback
            DB::statement('UPDATE galleries SET url = LEFT(url, 255) WHERE url IS NOT NULL');
            DB::statement('ALTER TABLE galleries MODIFY COLUMN url VARCHAR(255) NULL');
        }
    }
};
