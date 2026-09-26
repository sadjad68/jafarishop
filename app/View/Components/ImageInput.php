<?php

namespace App\View\Components;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class ImageInput extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $unique_id;

    public function __construct(
        public string $name,
        public string $label,
        public bool $deletable = false,
        public $deleteUrl = null,
        public $imageSrc = null,
        public array $validations = [],
        public $width = 500,
        public $height = 500,
        public $cropper = 0,
    ) {
        $this->unique_id = Str::random(10);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if ($this->cropper == "1") {
            return view('admin.components.forms.image-input');
        } else {
            return view('admin.components.forms.image-input-old');
        }

    }
}
