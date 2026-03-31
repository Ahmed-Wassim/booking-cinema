<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $columns = [
        'branches' => ['name', 'city', 'address'],
        'halls' => ['name'],
        'price_tiers' => ['name', 'description'],
        'movies' => ['title'],
    ];

    public function up(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        foreach ($this->columns as $table => $columns) {
            foreach ($columns as $column) {
                DB::statement(
                    "ALTER TABLE {$table} ALTER COLUMN {$column} TYPE jsonb USING CASE
                        WHEN {$column} IS NULL THEN NULL
                        ELSE jsonb_build_object('en', {$column})
                    END"
                );
            }
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        foreach ($this->columns as $table => $columns) {
            foreach ($columns as $column) {
                $columnType = $table === 'price_tiers' && $column === 'description' ? 'TEXT' : 'VARCHAR(255)';
                $fallbackLocale = config('app.fallback_locale', 'en');

                DB::statement(
                    "ALTER TABLE {$table} ALTER COLUMN {$column} TYPE {$columnType} USING COALESCE({$column}->>'en', {$column}->>'{$fallbackLocale}', {$column}::text)"
                );
            }
        }
    }
};
