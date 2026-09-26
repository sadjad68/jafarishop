<?php

namespace App\Modules\Course\DTO;

class CourseCategoryDTO
{
    public $title;
    public $description;
    public $url;
    public $image;
    public $active;

    public function __construct(string $title, ?string $description, string $url, $image,bool $active)
    {
        $this->title = $title;
        $this->description = $description;
        $this->url = $url;
        $this->image = $image;
        $this->active = $active;
    }
}
