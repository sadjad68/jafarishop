<?php

namespace App\Modules\Setting\DTO;

use App\Modules\General\Helper\NumberHelper;

class ThemeDTO
{
    public $title;
    public $description;
    public $url;
    public $parent_id;
    public $level;
    public $image;
    public $sample;

    public function __construct(string $title, string $description, string $url, ?string $parent_id, $image,?array $sample)
    {
        if ($parent_id != null){
            $parent = intval(NumberHelper::persian2LatinDigit(explode('|',$parent_id)[0]));
            $level = intval(NumberHelper::persian2LatinDigit(explode('|',$parent_id)[1]));
        }
        else{
            $parent = null;
            $level = 0;
        }
        $this->title = $title;
        $this->description = $description;
        $this->url = $url;
        $this->parent_id = $parent;
        $this->level = $level;
        $this->image = $image;
        $this->sample = $sample;
    }
}
