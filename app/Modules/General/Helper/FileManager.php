<?php

namespace App\Modules\General\Helper;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class FileManager
{

    public static function upload(UploadedFile $file, $folder = null, $filename = null,$quality = 80)
    {
        $random_name = TextHelper::random_str(5) . time();
        $filename = !is_null($filename) ? $filename : $random_name;
        $webp_filename = $filename . ".webp";
        $base_folder = strlen(config('setting.base_upload_folder')) > 0 ? config('setting.base_upload_folder') . "/uploads" : "uploads";

        if (!File::isDirectory(self::publicFilePath($base_folder))) {
            File::makeDirectory(self::publicFilePath($base_folder),0755,true);
        }
        if (!File::isDirectory(self::publicFilePath("$base_folder/$folder"))) {
            File::makeDirectory(self::publicFilePath("$base_folder/$folder"));
        }

        $manager = new ImageManager(new Driver());
        $manager->read($file)
            ->toWebp($quality)
            ->save(self::publicFilePath("$base_folder/$folder/$webp_filename"));
        return $webp_filename;
    }
    public static function uploadRaw(UploadedFile $file, $folder = null, $filename = null)
    {
        $random_name = TextHelper::random_str(5) . time();
        $filename = !is_null($filename) ? $filename : $random_name;
        $final_name = $filename . ".".$file->getClientOriginalExtension();
        $base_folder = strlen(config('setting.base_upload_folder')) > 0 ? config('setting.base_upload_folder') . "/uploads" : "uploads";

        if (!File::isDirectory(self::publicFilePath($base_folder))) {
            File::makeDirectory(self::publicFilePath($base_folder),0755,true);
        }

        if(count(explode('/',$folder) ) > 1){
            $path = "";
            foreach(explode('/',$folder) as $key=>$row){
                $path .= $key > 0 ? "/".$row : $row;
                if (!File::isDirectory(self::publicFilePath("$base_folder/$path"))) {
                    File::makeDirectory(self::publicFilePath("$base_folder/$path"));
                }
            }
        }else{
            if (!File::isDirectory(self::publicFilePath("$base_folder/$folder"))) {
                File::makeDirectory(self::publicFilePath("$base_folder/$folder"));
            }
        }

        $file->move(self::publicFilePath("$base_folder/$folder"), $final_name);
        return $final_name;
    }

    public static function delete($file_path): void
    {
        $base_folder = strlen(config('setting.base_upload_folder')) > 0 ? config('setting.base_upload_folder') . "/uploads" : "uploads";
        File::delete(self::publicFilePath($base_folder . '/' . $file_path));
    }

    public static function serveFile(string $file_path, string $notfound_path = 'assets/notfounds/default.jpg')
    {
        $base_upload_folder = config('setting.base_upload_folder');
        $base_serve_folder = config('setting.base_serve_folder');
        $absolute_path = self::publicFilePath($base_upload_folder . '/' . $file_path);
        return match (true) {
            is_dir($absolute_path) => asset($notfound_path),
            file_exists($absolute_path) => asset(trim("$base_serve_folder/$file_path", '/')),
            default => asset($notfound_path),
        };
    }
    public static function serveFileWithOutNotFound(string $file_path)
    {
        $base_upload_folder = config('setting.base_upload_folder');
        $base_serve_folder = config('setting.base_serve_folder');
        $absolute_path = self::publicFilePath($base_upload_folder . '/' . $file_path);
        return match (true) {
            file_exists($absolute_path) => asset(trim("$base_serve_folder/$file_path", '/')),
            default => '',
        };
    }

    public static function publicFilePath(string $relative = ''): string
    {
        $relative = ltrim(str_replace('\\', '/', $relative), '/');
        $root = rtrim(self::publicRoot(), '/');

        return $relative === '' ? $root : $root . '/' . $relative;
    }

    public static function publicRoot(): string
    {
        $configured = config('setting.public_path');
        if (is_string($configured) && $configured !== '') {
            return str_starts_with($configured, '/')
                ? rtrim($configured, '/')
                : base_path($configured);
        }

        $cpanelRoot = base_path('public_html');
        if (is_dir($cpanelRoot)) {
            return $cpanelRoot;
        }

        return public_path();
    }
}
