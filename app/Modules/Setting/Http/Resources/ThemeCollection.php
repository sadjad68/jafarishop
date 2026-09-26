<?php

namespace App\Modules\Setting\Http\Resources;

use App\Modules\General\Helper\FileManager;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ThemeCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        $themes = [];
        foreach ($this->collection as $row) {
            $themes[$row->key] = match ($row->type) {
                default => $row->value,
            };
        }
        return $themes;
    }

}
