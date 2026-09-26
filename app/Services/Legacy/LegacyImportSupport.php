<?php

namespace App\Services\Legacy;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class LegacyImportSupport
{
    public const OLD = 'PREVIOUS_CONVERT';
    public const NEW = 'NEXT_CONVERT';

    private ?int $rowLimit = null;

    private int $processed = 0;

    public function __construct(private ?OutputInterface $output = null)
    {
    }

    public function setRowLimit(?int $limit): void
    {
        $this->rowLimit = $limit !== null && $limit > 0 ? $limit : null;
        $this->processed = 0;
    }

    public function old()
    {
        return DB::connection(self::OLD);
    }

    public function new()
    {
        return DB::connection(self::NEW);
    }

    public function prepare(array $tables): LegacyImportStats
    {
        $stats = new LegacyImportStats();
        foreach ($tables as $table) {
            if (!Schema::connection(self::OLD)->hasTable($table)) {
                $this->warn('Legacy table missing: ' . $table);
                $stats->skipped++;
                continue;
            }
            if (Schema::connection(self::OLD)->hasColumn($table, 'is_convert')) {
                $stats->skipped++;
                continue;
            }
            Schema::connection(self::OLD)->table($table, function (Blueprint $blueprint) {
                $blueprint->integer('is_convert')->default(0);
            });
            $stats->inserted++;
        }

        return $stats;
    }

    public function eachPending(string $table, callable $callback, int $size = 300, ?int $limit = null): void
    {
        if (!Schema::connection(self::OLD)->hasTable($table)) {
            $this->warn('Legacy table missing: ' . $table);

            return;
        }

        $callLimit = $limit ?? $this->rowLimit;
        $seen = 0;
        if ($callLimit !== null && $callLimit < 1) {
            return;
        }
        if ($this->rowLimit !== null && $this->processed >= $this->rowLimit) {
            return;
        }

        $this->old()->table($table)->where('is_convert', 0)->chunkById($size, function ($rows) use ($callback, $callLimit, &$seen) {
            foreach ($rows as $row) {
                if ($this->rowLimit !== null && $this->processed >= $this->rowLimit) {
                    return false;
                }
                if ($callLimit !== null && $seen >= $callLimit) {
                    return false;
                }
                $callback($row);
                $seen++;
                $this->processed++;
            }
            if (($this->rowLimit !== null && $this->processed >= $this->rowLimit) || ($callLimit !== null && $seen >= $callLimit)) {
                return false;
            }
        });
    }

    public function pendingCount(string $table): int
    {
        if (!Schema::connection(self::OLD)->hasTable($table)) {
            return 0;
        }
        if (!Schema::connection(self::OLD)->hasColumn($table, 'is_convert')) {
            return 0;
        }

        return (int) $this->old()->table($table)->where('is_convert', 0)->count();
    }

    public function targetExists(string $table, int $id): bool
    {
        return $this->new()->table($table)->where('id', $id)->exists();
    }

    public function mark(string $table, int $id): void
    {
        $this->old()->table($table)->where('id', $id)->update(['is_convert' => 1]);
    }

    public function copyRow(string $sourceTable, int $id, string $targetTable, array $row, LegacyImportStats $stats): bool
    {
        if ($this->targetExists($targetTable, $id)) {
            $this->mark($sourceTable, $id);
            $stats->skipped++;

            return false;
        }

        try {
            $this->new()->table($targetTable)->insert($row);
            $this->mark($sourceTable, $id);
            $stats->inserted++;

            return true;
        } catch (Throwable $exception) {
            $stats->failed++;
            $this->warn($sourceTable . ' #' . $id . ' failed: ' . $exception->getMessage());

            return false;
        }
    }

    public function insertNew(string $targetTable, array $row, LegacyImportStats $stats, ?string $sourceTable = null, ?int $sourceId = null): bool
    {
        try {
            $this->new()->table($targetTable)->insert($row);
            if ($sourceTable !== null && $sourceId !== null) {
                $this->mark($sourceTable, $sourceId);
            }
            $stats->inserted++;

            return true;
        } catch (Throwable $exception) {
            $stats->failed++;
            $label = $sourceTable ? $sourceTable . ' #' . $sourceId : $targetTable;
            $this->warn($label . ' failed: ' . $exception->getMessage());

            return false;
        }
    }

    public function realignAutoIncrement(string $table): void
    {
        if (!Schema::connection(self::NEW)->hasTable($table) || !Schema::connection(self::NEW)->hasColumn($table, 'id')) {
            return;
        }
        $max = (int) $this->new()->table($table)->max('id');
        if ($max < 1) {
            return;
        }
        $next = $max + 1;
        $this->new()->statement('ALTER TABLE `' . $table . '` AUTO_INCREMENT = ' . $next);
    }

    public function warn(string $message): void
    {
        if ($this->output) {
            $this->output->writeln('<comment>' . $message . '</comment>');
        }
    }
}
