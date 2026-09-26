<?php

namespace App\Services\Legacy;

class LegacyImportStats
{
    public int $inserted = 0;
    public int $skipped = 0;
    public int $failed = 0;

    public function add(self $other): void
    {
        $this->inserted += $other->inserted;
        $this->skipped += $other->skipped;
        $this->failed += $other->failed;
    }

    public function toArray(): array
    {
        return [
            'inserted' => $this->inserted,
            'skipped' => $this->skipped,
            'failed' => $this->failed,
        ];
    }
}
