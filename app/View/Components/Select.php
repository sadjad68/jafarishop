<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Select extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public string $name,
        public string $label,
        public $options,
        public bool $searchable,
        public string|null $selectedOption,
        public $optionValue = null,
        public $optionLabel = null,
        public array $validations = [],
        public bool $clearable = false,
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('admin.components.forms.select');
    }
}
