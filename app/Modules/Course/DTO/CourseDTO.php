<?php

namespace App\Modules\Course\DTO;

class CourseDTO
{
    public $title;
    public $description;
    public $course_category_id;
    public $image;
    public $teacher_id;
    public $hours;
    public $minutes;
    public $type;
    public $price;
    public $discounted_price;
    public $url;
    public $h1;
    public $active;

    public function __construct(
        string $title,
        ?string $description,
        ?int $course_category_id,
        int $teacher_id,
        ?int $price,
        ?int $discounted_price,
        string $hours,
        string $minutes,
        string $type,
        string $url,
        ?string $h1,
        $image,
        bool $active)
    {
        $this->title = $title;
        $this->description = $description;
        $this->course_category_id = $course_category_id;
        $this->image = $image;
        $this->teacher_id = $teacher_id;
        $this->price = $price;
        $this->discounted_price = $discounted_price;
        $this->hours = $hours;
        $this->minutes = $minutes;
        $this->type = $type;
        $this->url = $url;
        $this->h1 = $h1;
        $this->active = $active;

    }
}
