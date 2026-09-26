<?php

namespace App\Services\Legacy;

use App\Modules\General\Helper\FileManager;
use Illuminate\Support\Facades\File;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Throwable;

class LegacyImageStore
{
    public function store(string $source, string $folder, string $filename, array $sizes): bool
    {
        if (!is_file($source)) {
            return false;
        }

        try {
            $manager = new ImageManager(new Driver());
            if ($sizes === []) {
                $destination = $this->absolute($folder . '/' . $filename);
                $this->ensureDir(dirname($destination));
                $manager->read($source)->scaleDown(1000, 1000)->toWebp(90)->save($destination);

                return true;
            }

            foreach ($sizes as $size => $dimensions) {
                $destination = $this->absolute($folder . '/' . $size . '/' . $filename);
                $this->ensureDir(dirname($destination));
                $manager->read($source)->scaleDown($dimensions[0], $dimensions[1])->toWebp(90)->save($destination);
            }

            return true;
        } catch (Throwable $exception) {
            return false;
        }
    }

    private function absolute(string $relative): string
    {
        $base = trim((string) config('setting.base_upload_folder'), '/');
        $path = ($base !== '' ? $base . '/' : '') . ltrim($relative, '/');

        return FileManager::publicFilePath($path);
    }

    private function ensureDir(string $directory): void
    {
        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }
    }
}
