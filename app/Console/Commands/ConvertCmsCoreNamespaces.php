<?php

namespace App\Console\Commands;

use App\Services\CmsCoreNamespaceConverter;
use Illuminate\Console\Command;

class ConvertCmsCoreNamespaces extends Command
{
    protected $signature = 'convert:cmscore-namespaces {--dry-run : Count matching rows without updating}';
    protected $description = 'Replace stored Rahweb\\CmsCore\\ class names with App\\ in morph and text columns';

    public function handle(CmsCoreNamespaceConverter $converter): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $result = $converter->convert($dryRun);

        if ($result['tables'] === []) {
            $this->info('No Rahweb\\CmsCore\\ values found.');
            return self::SUCCESS;
        }

        $this->table(
            ['Table', 'Column', 'Matched', 'Updated', 'Morph'],
            array_map(static function (array $row) {
                return [
                    $row['table'],
                    $row['column'],
                    $row['matched'],
                    $row['updated'],
                    $row['morph'] ? 'yes' : 'no',
                ];
            }, $result['tables'])
        );

        $this->info(sprintf(
            '%s: matched %d row(s), updated %d, remaining %d.',
            $dryRun ? 'Dry run' : 'Convert',
            $result['total_matched'],
            $result['total_updated'],
            $result['remaining']
        ));

        return self::SUCCESS;
    }
}
