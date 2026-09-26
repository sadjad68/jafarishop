<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('highlights')) {
            return;
        }

        $rows = DB::table('highlights')
            ->whereNull('deleted_at')
            ->orderByDesc('id')
            ->get();

        $keepPlace = [];
        $keepTag = [];

        foreach ($rows as $row) {
            $target = strtolower(trim((string) ($row->target ?? 'place')));
            if ($target === '') {
                $target = 'place';
            }
            $isDesktop = strtolower(trim((string) ($row->type ?? ''))) === 'desktop';

            if ($target === 'tag' && $row->tag_id) {
                $key = (int) $row->tag_id;
                if (! isset($keepTag[$key])) {
                    $keepTag[$key] = $row;
                } elseif (! $this->isDesktopRow($keepTag[$key]) && $isDesktop) {
                    $keepTag[$key] = $row;
                }
                continue;
            }

            $place = (string) ($row->place ?? '');
            if ($place === '') {
                continue;
            }
            if (! isset($keepPlace[$place])) {
                $keepPlace[$place] = $row;
            } elseif (! $this->isDesktopRow($keepPlace[$place]) && $isDesktop) {
                $keepPlace[$place] = $row;
            }
        }

        $keepIds = [];
        foreach ($keepPlace as $row) {
            $keepIds[] = $row->id;
        }
        foreach ($keepTag as $row) {
            $keepIds[] = $row->id;
        }

        if (count($keepIds) === 0) {
            return;
        }

        DB::table('highlights')
            ->whereNull('deleted_at')
            ->whereNotIn('id', $keepIds)
            ->update(['deleted_at' => now()]);

        DB::table('highlights')
            ->whereIn('id', $keepIds)
            ->update(['type' => 'desktop']);
    }

    public function down(): void
    {
        // Duplicate mobile rows cannot be restored.
    }

    private function isDesktopRow($row): bool
    {
        return strtolower(trim((string) ($row->type ?? ''))) === 'desktop';
    }
};
