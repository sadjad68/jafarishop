<?php

namespace App\Modules\Product\DTO;

use App\Modules\Product\Http\Requests\BrandRequest;
use App\Modules\Product\Http\Requests\ProductCategoryRequest;
use Illuminate\Http\UploadedFile;
use App\Modules\General\Helper\FileManager;

class BrandDTO
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
    public bool $show_in_first_page;
    public function showFirstPage(): bool
    {
        return $this->show_in_first_page;
    }
    public static function fromRequest(BrandRequest $request)
    {

        $self = new self();
        $self->title = $request->get('title');
        $self->description = $request->get('description');
        $self->image = $request->file('image');
        $self->active = $request->has('active');
        $self->show_in_first_page = $request->has('show_in_first_page');
        $self->url = trim(str_replace(' ', '-',@$request->get('url')));
        return $self;
    }
}
