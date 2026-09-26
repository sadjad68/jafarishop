<?php

namespace App\Modules\Product\DTO;

use App\Modules\Product\Http\Requests\ProductCategoryRequest;
use Illuminate\Http\UploadedFile;

class ProductCategoryDTO
{
    protected string $title;

    public function getTitle(): string
    {
        return $this->title;
    }

    protected UploadedFile|null $image;

    public function getImage(): ?UploadedFile
    {
        return $this->image;
    }

    protected string $url;

    public function getUrl(): string
    {
        return $this->url;
    }

    protected string|null $description;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public bool $active;

    public function getActive(): bool
    {
        return $this->active;
    }
    public bool $showInSite;

    public function getShowInSite():bool
    {
        return $this->showInSite;
    }

    public int|null $parent_id;

    public function getParentId(): ?int
    {
        return $this->parent_id;
    }

    public array $filter_specifications;

    public function getFilterSpecifications(): array
    {
        $list = [];
        foreach ($this->filter_specifications ?? [] as $ids) {
            $list = array_merge($list, $ids);
        }
        return $list;
    }

    protected string $show_in_first_page;

    public function isShowInFirstPage(): bool
    {
        return $this->show_in_first_page;
    }

    protected ?string $have_price_range;

    public function getHavePriceRange(): ?string
    {
        return $this->have_price_range;
    }

    protected ?string $min_price;

    public function getMinPrice(): ?string
    {
        return $this->min_price;
    }

    protected ?string $max_price;

    public function getMaxPrice(): ?string
    {
        return $this->max_price;
    }

    public static function fromRequest(ProductCategoryRequest $request)
    {

        $self = new self();
        $self->title = $request->get('title');
        $self->description = $request->get('description');
        $self->image = $request->file('image');
        $self->filter_specifications = $request->get('filter_specifications') ?? [];
        $self->active = $request->has('active');
        $self->showInSite = $request->has('show_in_site');
        $self->show_in_first_page = $request->has('show_in_first_page');
        $self->parent_id = $request->get('parent_id');
        $self->url = trim(str_replace(' ', '-', @$request->get('url')));
        $self->have_price_range = $request->has('have_price_range') ? 1 : 0;
        $self->min_price = $request->get('min_price');
        $self->max_price = $request->get('max_price');
        return $self;
    }
}
