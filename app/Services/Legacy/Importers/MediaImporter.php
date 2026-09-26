<?php

namespace App\Services\Legacy\Importers;

use App\Services\Legacy\LegacyImageStore;
use App\Services\Legacy\LegacyImportStats;
use App\Services\Legacy\LegacyImportSupport;
use App\Services\Legacy\LegacyMapper;

class MediaImporter
{
    public function __construct(
        private LegacyImportSupport $support,
        private ?LegacyImageStore $images = null
    ) {
        $this->images = $images ?? new LegacyImageStore();
    }

    public function import(): LegacyImportStats
    {
        $stats = new LegacyImportStats();
        $root = $this->mediaRoot();
        if (!is_dir($root)) {
            $this->support->warn('Legacy media directory not found: ' . $root);
            $stats->failed++;

            return $stats;
        }

        $rows = $this->support->old()->table('media')
            ->where('is_convert', 0)
            ->get(['id', 'model_type', 'model_id', 'collection_name', 'file_name', 'order_column']);
        $rows = $rows->sort(function ($left, $right) {
            $order = ((int) ($left->order_column ?? 0)) <=> ((int) ($right->order_column ?? 0));

            return $order !== 0 ? $order : ((int) $left->id) <=> ((int) $right->id);
        });

        foreach ($rows as $row) {
            $this->importOne($row, $root, $stats);
        }

        return $stats;
    }

    public static function mediaRoot(): string
    {
        $configured = env('LEGACY_MEDIA_PATH');
        if (is_string($configured) && $configured !== '') {
            return rtrim($configured, '/');
        }

        return '/Users/sadjadsamrad/Downloads/backup-9.15.2026_15-14-52_topickala/homedir/public_html/media';
    }

    private function importOne(object $row, string $root, LegacyImportStats $stats): void
    {
        $id = (int) $row->id;
        $modelId = (int) $row->model_id;
        $type = ltrim(str_replace('\\\\', '\\', (string) $row->model_type), '\\');
        $filename = LegacyMapper::mediaFilename($id);
        $source = $this->sourceFile($root, $id, (string) $row->file_name);
        $plan = $this->plan($type, (string) $row->collection_name);

        if ($plan === null) {
            $this->support->mark('media', $id);
            $stats->skipped++;

            return;
        }

        if (!$this->ownerExists($plan['owner'], $modelId, $filename)) {
            $stats->skipped++;
            $this->support->warn('Owner missing for media #' . $id . ' (' . $type . ' #' . $modelId . ')');

            return;
        }

        if ($source === null || !$this->images->store($source, $plan['folder'], $filename, $plan['sizes'])) {
            $this->support->mark('media', $id);
            $stats->failed++;
            $this->support->warn('Media file missing or unreadable: #' . $id);

            return;
        }

        $this->attach($plan['owner'], $modelId, $filename);
        $this->support->mark('media', $id);
        $stats->inserted++;
    }

    private function sourceFile(string $root, int $id, string $fileName): ?string
    {
        $direct = $root . '/' . $id . '/' . $fileName;
        if (is_file($direct)) {
            return $direct;
        }
        $matches = glob($root . '/' . $id . '/*');
        if (!$matches) {
            return null;
        }
        foreach ($matches as $match) {
            if (is_file($match)) {
                return $match;
            }
        }

        return null;
    }

    private function plan(string $type, string $collection): ?array
    {
        $productSizes = [
            'big' => [1000, 1000],
            'medium' => [500, 500],
            'small' => [75, 75],
        ];

        return match ($type) {
            'App\\Models\\Product' => ['owner' => 'product', 'folder' => 'uploads/product', 'sizes' => $productSizes],
            'App\\Models\\Brand' => ['owner' => 'brand', 'folder' => 'uploads/brand', 'sizes' => []],
            'App\\Models\\Category' => ['owner' => 'category', 'folder' => 'uploads/product-category', 'sizes' => $productSizes],
            'App\\Models\\Post' => ['owner' => 'blog', 'folder' => 'uploads/blog', 'sizes' => ['big' => [1000, 1000], 'small' => [100, 100]]],
            'App\\Models\\PostCategory' => ['owner' => 'blog_category', 'folder' => 'uploads/blog-category', 'sizes' => []],
            'App\\Models\\Slider' => ['owner' => 'banner', 'folder' => 'uploads/banner', 'sizes' => ['big' => [1600, 700]]],
            'App\\Models\\Setting' => in_array($collection, ['banner1', 'banner2', 'banner3', 'banner4', 'banner5', 'banner6', 'banner7', 'banner8', 'bannerright', 'bennerleft'], true)
                ? ['owner' => 'setting_banner', 'folder' => 'uploads/banner', 'sizes' => ['big' => [1000, 500]]]
                : null,
            default => null,
        };
    }

    private function ownerExists(string $owner, int $modelId, string $filename): bool
    {
        return match ($owner) {
            'product' => $this->support->targetExists('products', $modelId),
            'brand' => $this->support->targetExists('brands', $modelId),
            'category' => $this->support->targetExists('product_categories', $modelId),
            'blog' => $this->support->targetExists('blogs', $modelId),
            'blog_category' => $this->support->targetExists('blog_categories', $modelId),
            'banner' => $this->support->targetExists('banners', $modelId),
            'setting_banner' => $this->support->new()->table('highlights')->where('image', $filename)->exists(),
            default => false,
        };
    }

    private function attach(string $owner, int $modelId, string $filename): void
    {
        $now = date('Y-m-d H:i:s');
        match ($owner) {
            'product' => $this->attachProduct($modelId, $filename, $now),
            'brand' => $this->fillImage('brands', $modelId, $filename),
            'category' => $this->fillImage('product_categories', $modelId, $filename),
            'blog' => $this->fillImage('blogs', $modelId, $filename),
            'blog_category' => $this->fillImage('blog_categories', $modelId, $filename),
            'banner', 'setting_banner' => null,
            default => null,
        };
    }

    private function attachProduct(int $productId, string $filename, string $now): void
    {
        $exists = $this->support->new()->table('images')
            ->where('product_id', $productId)
            ->where('image', $filename)
            ->exists();
        $hasThumbnail = $this->support->new()->table('images')
            ->where('product_id', $productId)
            ->where('thumbnail', 1)
            ->exists();
        if (!$exists) {
            $this->support->new()->table('images')->insert([
                'image' => $filename,
                'active' => 1,
                'thumbnail' => $hasThumbnail ? 0 : 1,
                'product_id' => $productId,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ]);
        }
        $current = $this->support->new()->table('products')->where('id', $productId)->value('image');
        if ($current === null || $current === '') {
            $this->support->new()->table('products')->where('id', $productId)->update([
                'image' => $filename,
                'updated_at' => $now,
            ]);
        }
    }

    private function fillImage(string $table, int $id, string $filename): void
    {
        $current = $this->support->new()->table($table)->where('id', $id)->value('image');
        if ($current === null || $current === '') {
            $this->support->new()->table($table)->where('id', $id)->update([
                'image' => $filename,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
