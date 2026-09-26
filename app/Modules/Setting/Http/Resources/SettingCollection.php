<?php

namespace App\Modules\Setting\Http\Resources;

use App\Modules\General\Helper\FileManager;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SettingCollection extends ResourceCollection
{

    public function toArray($request): array
    {
        $settings = [];
        $keysToCheck = ['video_cover'];
        foreach ($this->collection as $row) {
            $fileUrl = null;
            if (in_array($row->key, $keysToCheck) && @$row->value != null) {
                if (file_exists('uploads/setting/' . @$row->value)) {
                    $fileUrl = config('cms-assistant.public-base-url') . '/uploads/setting/' . @$row->value;
                }

            }
            $settings[$row->key] = match ($row->type) {
                "input_file" => self::fileFormat($row),
                "input_file_about" => self::fileFormat($row),
                "array" => self::arrayFormat($row->value),
                "array_files" => self::arrayFileFormat($row->value),
                "work_hours" => self::jsonFormat($row->value),
                default => $row->value,
            };
            if (in_array($row->key, $keysToCheck)) {
                $settings[$row->key] = $fileUrl;
            }
        }
        return $settings;
    }

    private function jsonFormat($value)
    {
        return json_decode($value,true);
    }

    private function arrayFormat($value): array
    {
        return explode(",", $value);
    }

    private function arrayFileFormat($value): array
    {
        $files = self::arrayFormat($value);
        $files_format = [];
        foreach ($files as $row) {
            $files_format[] = self::fileFormat($row);
        }
        return $files_format;
    }

    private function fileFormat($value)
    {
        $image = "default.jpg";

        if (@$value['options']['image']){
            $image = $value['options']['image'];
        }
        return FileManager::serveFile(
            'uploads/setting/' . $value['value'], 'assets/notfounds/'.$image
        );
    }

}
