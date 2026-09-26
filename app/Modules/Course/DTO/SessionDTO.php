<?php

namespace App\Modules\Course\DTO;

class SessionDTO
{
    public $title;
    public $description;
    public $free;
    public $thumbnail;
    public $active;
    public $hours;
    public $minutes;
    public $files;
    public $course_id;


    public function __construct(
        string $title,
        ?string $description,
        bool $free,
        bool $active,
        string $hours,
        string $minutes,
        $thumbnail,
        $files,
        ?int $course_id,
        )
    {
        $this->title = $title;
        $this->description = $description;
        $this->free = $free;
        $this->active = $active;
        $this->hours = $hours;
        $this->minutes = $minutes;
        $this->thumbnail = $thumbnail;
        $this->files = $files;
        $this->course_id = $course_id;
    }
}

