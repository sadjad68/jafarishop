<?php

namespace App\View\Components;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class PasswordInput extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $unique_id;
    public $pure_validations;
    public $valuable_validations;

    public function __construct(
        public string $name,
        public string $label,
        public array  $validations
    ) {
        $this->valuable_validations = array_filter($validations, function ($key) {
            return !is_int($key);
        }, ARRAY_FILTER_USE_KEY);
        $this->pure_validations = array_filter($validations, function ($key) {
            return is_int($key);
        }, ARRAY_FILTER_USE_KEY);

        $this->unique_id = Str::random(10);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('admin.components.forms.password-input');
    }
}
