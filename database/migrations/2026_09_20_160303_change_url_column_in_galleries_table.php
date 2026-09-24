<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * SQLite does not enforce VARCHAR length limits, so this migration is a no-op.
 * It exists to mark a schema intent and has already been applied via the
 * original table definition (url is nullable varchar with no enforced limit).
 */
return new class extends Migration
{
    public function up(): void
    {
        // SQLite doesn't need ALTER COLUMN — varchar has no length limit.
        // No Doctrine/DBAL needed. This migration is a safe no-op.
    }

    public function down(): void
    {
        // No-op reverse.
    }
};
