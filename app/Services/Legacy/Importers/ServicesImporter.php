<?php

namespace App\Services\Legacy\Importers;

use App\Services\Legacy\LegacyImportStats;
use App\Services\Legacy\LegacyImportSupport;
use App\Services\Legacy\LegacyMapper;

class ServicesImporter
{
    public function __construct(private LegacyImportSupport $support)
    {
    }

    public function import(): LegacyImportStats
    {
        $stats = new LegacyImportStats();
        $this->services($stats);
        $this->support->realignAutoIncrement('services');
        $this->support->realignAutoIncrement('service_fees');

        return $stats;
    }

    private function services(LegacyImportStats $stats): void
    {
        $this->support->eachPending('services', function ($row) use ($stats) {
            $id = (int) $row->id;
            $title = LegacyMapper::combineText($row->name ?? null) ?? ('service-' . $id);
            $deletedAt = LegacyMapper::nullableTimestamp($row->deleted_at ?? null);
            $now = LegacyMapper::timestamp($row->created_at ?? null);
            $price = LegacyMapper::positivePrice($row->price ?? null);
            $inserted = $this->support->copyRow('services', $id, 'services', [
                'id' => $id,
                'title' => $title,
                'description' => $price !== null ? ('قیمت: ' . number_format((float) $price) . ' تومان') : null,
                'short_description' => $price !== null ? ($price . ' تومان') : null,
                'url' => LegacyMapper::serviceUrl($id, $title),
                'image' => null,
                'status' => $deletedAt ? 0 : 1,
                'parent_id' => null,
                'show_in_first_page' => 0,
                'show_in_menu' => 1,
                'show_in_footer' => 0,
                'sort' => $id,
                'created_at' => $now,
                'updated_at' => LegacyMapper::timestamp($row->updated_at ?? $now),
                'deleted_at' => $deletedAt,
            ], $stats);

            if ((!$inserted && !$this->support->targetExists('services', $id)) || $price === null) {
                return;
            }

            $feeExists = $this->support->new()->table('service_fees')
                ->where('service_id', $id)
                ->where('minimum_price', (int) $price)
                ->exists();
            if ($feeExists) {
                return;
            }

            $this->support->insertNew('service_fees', [
                'description' => $title,
                'minimum_price' => (int) $price,
                'maximum_price' => (int) $price,
                'service_id' => $id,
                'created_at' => $now,
                'updated_at' => LegacyMapper::timestamp($row->updated_at ?? $now),
                'deleted_at' => null,
            ], $stats);
        });
    }
}
