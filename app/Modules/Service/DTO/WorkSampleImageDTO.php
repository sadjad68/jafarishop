<?php

namespace App\Modules\Service\DTO;

class WorkSampleImageDTO
{
    public $work_sample_id;
    public $image;
    public $double;

    public function __construct($image, $WorkSampleId, int $double)
    {
        $this->image = $image;
        $this->work_sample_id = $WorkSampleId;
        $this->double = $double;
    }
}
