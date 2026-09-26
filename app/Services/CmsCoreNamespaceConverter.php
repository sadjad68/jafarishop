<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CmsCoreNamespaceConverter
{
    /**
     * Tables that store PHP serialized / cache payloads where a blind REPLACE
     * can corrupt lengths. Morph *_type columns in these tables are still converted.
     *
     * @var array<int, string>
     */
    protected array $skipNonMorphTables = [
        'migrations',
        'sessions',
        'cache',
        'cache_locks',
        'password_resets',
        'password_reset_tokens',
    ];

    /**
     * Known polymorphic type columns (also discovered via schema scan).
     *
     * @var array<int, array{0: string, 1: string}>
     */
    protected array $knownMorphColumns = [
        ['comments', 'commentable_type'],
        ['seo_metas', 'seoable_type'],
        ['faqs', 'faqable_type'],
        ['taggables', 'taggable_type'],
        ['prices', 'priceable_type'],
        ['discountable', 'discountable_type'],
    ];

    /**
     * Ordered replacements. Longer / escaped forms first.
     *
     * @return array<int, array{from: string, to: string}>
     */
    public static function replacements(): array
    {
        return [
            [
                'from' => 'Rahweb\\\\CmsCore\\\\',
                'to' => 'App\\\\',
            ],
            [
                'from' => 'Rahweb\\CmsCore\\',
                'to' => 'App\\',
            ],
            [
                'from' => 'Rahweb/CmsCore/',
                'to' => 'App/',
            ],
        ];
    }

    public static function normalizeClassName(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return $value;
        }

        foreach (self::replacements() as $pair) {
            if (str_contains($value, $pair['from'])) {
                $value = str_replace($pair['from'], $pair['to'], $value);
            }
        }

        return $value;
    }

    /**
     * @return array<string, mixed>
     */
    public function convert(bool $dryRun = false): array
    {
        $targets = $this->discoverTargets();
        $tables = [];
        $skipped = $this->skippedTablesWithMatches();
        $totalMatched = 0;
        $totalUpdated = 0;

        foreach ($targets as $target) {
            $table = $target['table'];
            $column = $target['column'];
            $isMorph = $target['morph'];

            $matched = $this->countMatches($table, $column);
            if ($matched === 0) {
                continue;
            }

            $updated = 0;
            if (!$dryRun) {
                $updated = $this->replaceInColumn($table, $column);
            }

            $row = [
                'table' => $table,
                'column' => $column,
                'matched' => $matched,
                'updated' => $dryRun ? 0 : $updated,
                'morph' => $isMorph,
            ];

            $tables[] = $row;
            $totalMatched += $matched;
            $totalUpdated += $row['updated'];
        }

        return [
            'success' => true,
            'dry_run' => $dryRun,
            'from' => 'Rahweb\\CmsCore\\',
            'to' => 'App\\',
            'tables' => $tables,
            'skipped' => $skipped,
            'total_matched' => $totalMatched,
            'total_updated' => $totalUpdated,
            'remaining' => $dryRun ? $totalMatched : $this->countRemaining($targets),
        ];
    }

    /**
     * @param array<int, array{table: string, column: string, morph: bool}> $targets
     */
    protected function countRemaining(array $targets): int
    {
        $remaining = 0;
        foreach ($targets as $target) {
            $remaining += $this->countMatches($target['table'], $target['column']);
        }

        return $remaining;
    }

    /**
     * @return array<int, array{table: string, column: string, morph: bool}>
     */
    protected function discoverTargets(): array
    {
        $found = [];
        $index = [];

        foreach ($this->knownMorphColumns as [$table, $column]) {
            if (!Schema::hasTable($table) || !Schema::hasColumn($table, $column)) {
                continue;
            }
            if (!$this->isSafeIdentifier($table) || !$this->isSafeIdentifier($column)) {
                continue;
            }
            $key = $table . '.' . $column;
            $index[$key] = true;
            $found[] = [
                'table' => $table,
                'column' => $column,
                'morph' => true,
            ];
        }

        foreach ($this->schemaStringColumns() as $row) {
            $table = $row['table'];
            $column = $row['column'];
            if (!$this->isSafeIdentifier($table) || !$this->isSafeIdentifier($column)) {
                continue;
            }
            if (in_array($table, $this->skipNonMorphTables, true)) {
                continue;
            }
            $key = $table . '.' . $column;
            if (isset($index[$key])) {
                continue;
            }
            $index[$key] = true;
            $found[] = [
                'table' => $table,
                'column' => $column,
                'morph' => str_ends_with($column, '_type'),
            ];
        }

        return $found;
    }

    /**
     * @return array<int, array{table: string, column: string}>
     */
    protected function schemaStringColumns(): array
    {
        try {
            $database = Schema::getConnection()->getDatabaseName();
            $rows = DB::select(
                'SELECT TABLE_NAME AS table_name, COLUMN_NAME AS column_name
                 FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA = ?
                   AND DATA_TYPE IN (?, ?, ?, ?, ?, ?, ?)',
                [$database, 'varchar', 'char', 'text', 'tinytext', 'mediumtext', 'longtext', 'json']
            );
        } catch (\Throwable $e) {
            return [];
        }

        $out = [];
        foreach ($rows as $row) {
            $out[] = [
                'table' => (string) $row->table_name,
                'column' => (string) $row->column_name,
            ];
        }

        return $out;
    }

    protected function countMatches(string $table, string $column): int
    {
        try {
            $quotedTable = $this->quote($table);
            $quotedColumn = $this->quote($column);
            $bindings = [];
            $clauses = [];

            foreach (self::replacements() as $pair) {
                $clauses[] = "INSTR({$quotedColumn}, ?) > 0";
                $bindings[] = $pair['from'];
            }

            $sql = "SELECT COUNT(*) AS aggregate FROM {$quotedTable} WHERE " . implode(' OR ', $clauses);
            $result = DB::selectOne($sql, $bindings);

            return (int) ($result->aggregate ?? 0);
        } catch (\Throwable $e) {
            return 0;
        }
    }

    protected function replaceInColumn(string $table, string $column): int
    {
        try {
            $quotedTable = $this->quote($table);
            $quotedColumn = $this->quote($column);
            $updated = 0;

            foreach (self::replacements() as $pair) {
                $sql = "UPDATE {$quotedTable} SET {$quotedColumn} = REPLACE({$quotedColumn}, ?, ?) WHERE INSTR({$quotedColumn}, ?) > 0";
                $updated += (int) DB::update($sql, [$pair['from'], $pair['to'], $pair['from']]);
            }

            return $updated;
        } catch (\Throwable $e) {
            return 0;
        }
    }

    /**
     * @return array<int, array{table: string, matched: int, reason: string}>
     */
    protected function skippedTablesWithMatches(): array
    {
        $skipped = [];
        foreach ($this->skipNonMorphTables as $table) {
            if (!Schema::hasTable($table) || !$this->isSafeIdentifier($table)) {
                continue;
            }
            $matched = 0;
            foreach (Schema::getColumnListing($table) as $column) {
                if (!$this->isSafeIdentifier($column)) {
                    continue;
                }
                $matched += $this->countMatches($table, $column);
            }
            if ($matched > 0) {
                $skipped[] = [
                    'table' => $table,
                    'matched' => $matched,
                    'reason' => 'Skipped serialized/cache table; inspect manually if needed.',
                ];
            }
        }

        return $skipped;
    }

    protected function isSafeIdentifier(string $name): bool
    {
        return (bool) preg_match('/^[A-Za-z0-9_]+$/', $name);
    }

    protected function quote(string $name): string
    {
        return '`' . $name . '`';
    }
}
