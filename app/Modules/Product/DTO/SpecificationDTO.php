<?php

namespace App\Modules\Product\DTO;

use App\Modules\Product\Http\Requests\SpecificationRequest;

class SpecificationDTO
{
    protected string $title;
    public function getTitle(): string
    {
        return $this->title;
    }
    protected string $type;
    public function getType(): string
    {
        return $this->type;
    }
    public bool $active;
    public function getActive(): bool
    {
        return $this->active;
    }
    public bool $is_filter;
    public function getIsFilter(): bool
    {
        return $this->is_filter;
    }
    public bool $is_color;
    public function getIsColor(): bool
    {
        return $this->is_color;
    }
    public int|null $parent_id;
    public function getParentId(): ?int
    {
        return $this->parent_id;
    }

    public function getCategories(): ?array
    {
        return $this->categories;
    }
    protected string|null $color_code;
    public function getColorCode(): string|null
    {
        return $this->color_code;
    }
    public static function fromRequest(SpecificationRequest $request)
    {


        $self = new self();
        $self->title = $request->get('title');
        $self->active = $request->has('active');
        $self->is_filter = $request->get('type') != "text" ? $request->has('is_filter') : 0;
        $self->is_color = $request->get('type') != "text" ? $request->has('is_color') : 0;
        $self->parent_id = $request->get('parent_id');
        $self->categories = $request->get('categories');
        $self->type = $request->get('type');
        $self->color_code = @$request->get('color_code');
        return $self;
    }
}
