<?php

namespace App\Console\Commands;

use App\Services\Legacy\TopickalaLegacyImporter;
use Illuminate\Console\Command;
use InvalidArgumentException;
use Throwable;

class ImportTopickalaLegacy extends Command
{
    protected $signature = 'legacy:import
        {section=all : prepare, catalog, content, services, users, commerce, settings, redirects, media, or all}';

    protected $description = 'Import the old Topickala shop into the current module schema';

    public function handle(): int
    {
        $section = (string) $this->argument('section');
        $importer = TopickalaLegacyImporter::make($this->output);
        $allowed = array_merge(['all'], $importer->sections());
        if (!in_array($section, $allowed, true)) {
            $this->error('Unknown section. Use one of: ' . implode(', ', $allowed));

            return self::FAILURE;
        }

        $sections = $section === 'all' ? $importer->sections() : [$section];
        $failed = false;

        foreach ($sections as $name) {
            $this->info('Importing ' . $name . '...');
            try {
                $stats = $importer->run($name);
            } catch (InvalidArgumentException $exception) {
                $this->error($exception->getMessage());

                return self::FAILURE;
            } catch (Throwable $exception) {
                $this->error($name . ' stopped: ' . $exception->getMessage());
                $failed = true;
                continue;
            }
            $this->line(sprintf(
                '%s: inserted %d, skipped %d, failed %d',
                $name,
                $stats->inserted,
                $stats->skipped,
                $stats->failed
            ));
            if ($stats->failed > 0) {
                $failed = true;
            }
        }

        return $failed ? self::FAILURE : self::SUCCESS;
    }
}
