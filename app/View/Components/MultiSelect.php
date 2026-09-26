<?php

namespace App\View\Components;

use Illuminate\View\Component;

class MultiSelect extends Component
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
        public array $selectedOptions,
        public $optionValue = null,
        public $optionLabel = null,
        public array $validations = [],
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('admin.components.forms.multi-select');
    }
}
