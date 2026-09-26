<?php

namespace App\Services\Legacy;

use App\Services\Legacy\Importers\CatalogImporter;
use App\Services\Legacy\Importers\CommerceImporter;
use App\Services\Legacy\Importers\ContentImporter;
use App\Services\Legacy\Importers\MediaImporter;
use App\Services\Legacy\Importers\RedirectImporter;
use App\Services\Legacy\Importers\ServicesImporter;
use App\Services\Legacy\Importers\SettingsImporter;
use App\Services\Legacy\Importers\UserImporter;
use InvalidArgumentException;
use Symfony\Component\Console\Output\OutputInterface;
use Illuminate\Support\Facades\Schema;

class TopickalaLegacyImporter
{
    public function __construct(private LegacyImportSupport $support)
    {
    }

    public static function make(?OutputInterface $output = null): self
    {
        return new self(new LegacyImportSupport($output));
    }

    public function setRowLimit(?int $limit): void
    {
        $this->support->setRowLimit($limit);
    }

    public function sections(): array
    {
        return ['prepare', 'catalog', 'content', 'services', 'users', 'commerce', 'settings', 'redirects', 'media'];
    }

    public function tablesFor(string $section): array
    {
        return match ($section) {
            'catalog' => ['categories', 'brands', 'products', 'seo'],
            'content' => ['post_categories', 'posts', 'questions', 'comments', 'contacts'],
            'services' => ['services'],
            'users' => ['users'],
            'commerce' => ['discounts', 'orders', 'order_items', 'order'],
            'settings' => ['settings', 'sliders'],
            'redirects' => ['redirect'],
            'media' => ['media'],
            default => [],
        };
    }

    public function remaining(string $section): int
    {
        if ($section === 'prepare') {
            $missing = 0;
            foreach ($this->sourceTables() as $table) {
                if (!Schema::connection(LegacyImportSupport::OLD)->hasTable($table)) {
                    continue;
                }
                if (!Schema::connection(LegacyImportSupport::OLD)->hasColumn($table, 'is_convert')) {
                    $missing++;
                }
            }

            return $missing;
        }

        $total = 0;
        foreach ($this->tablesFor($section) as $table) {
            $total += $this->support->pendingCount($table);
        }

        return $total;
    }

    public function sourceTables(): array
    {
        return [
            'categories',
            'brands',
            'products',
            'posts',
            'post_categories',
            'questions',
            'comments',
            'contacts',
            'seo',
            'services',
            'users',
            'discounts',
            'orders',
            'order_items',
            'order',
            'redirect',
            'sliders',
            'settings',
            'media',
        ];
    }

    public function run(string $section): LegacyImportStats
    {
        return match ($section) {
            'prepare' => $this->support->prepare($this->sourceTables()),
            'catalog' => (new CatalogImporter($this->support))->import(),
            'content' => (new ContentImporter($this->support))->import(),
            'services' => (new ServicesImporter($this->support))->import(),
            'users' => (new UserImporter($this->support))->import(),
            'commerce' => (new CommerceImporter($this->support))->import(),
            'settings' => (new SettingsImporter($this->support))->import(),
            'redirects' => (new RedirectImporter($this->support))->import(),
            'media' => (new MediaImporter($this->support))->import(),
            default => throw new InvalidArgumentException('Unknown legacy import section: ' . $section),
        };
    }
}
