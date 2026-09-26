<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CheckBox extends Component
{

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public string $name,
        public string $label,
        public array $validations = [],
        public $valueData = null,
        public $value = 1,


    )
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('admin.components.forms.check-box');
    }
}
