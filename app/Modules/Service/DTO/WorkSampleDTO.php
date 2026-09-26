<?php

namespace App\Modules\Service\DTO;
use App\Modules\Service\Http\Requests\WorkSampleRequest;
use Illuminate\Http\UploadedFile;

class WorkSampleDTO
{

    protected string $title;

    public function getTitle(): string
    {

        return $this->title;
    }

    protected string|null $short_description;

    public function getShortDescription(): string|null
    {
        return $this->short_description;
    }
    protected string|null $description;

    public function getDescription(): string|null
    {

        return $this->description;
    }


    protected int|null $show_in_first_page;
    public function getShowInFirstPage(): int|null
    {

        return $this->show_in_first_page;
    }
    protected array $services = [];

    public function getServices(): array
    {
        return $this->services;
    }

    protected UploadedFile|array $images;

    public function getImage(): UploadedFile|array
    {

        return $this->images;
    }
    protected int|null $double_image;
    public function getDoubleImage(): int|null
    {

        return $this->double_image;
    }
    protected int|null $thumbnail;
    public function getThumbnail(): int|null
    {

        return $this->thumbnail;
    }
    protected UploadedFile|null $before_image;

    public function getBeforeImage(): UploadedFile|null
    {
        return $this->before_image;
    }
    protected UploadedFile|null $after_image;

    public function getAfterImage(): UploadedFile|null
    {
        return $this->after_image;
    }
    protected int|null $has_page;
    public function getHasPage(): int|null
    {

        return $this->has_page;
    }
    protected string|null $url;
    public function getUrl(): string|null
    {
        return $this->has_page == 1 ? $this->url : null;
    }

    public static function fromRequest(WorkSampleRequest $request)
    {

        $self = new self();
        $imgs = $request->file('images') ? $request->file('images') : [];
        $img = $request->file('image') == null ? [] : $request->file('image');
        $self->title = trim($request->get('title'));
        $self->description = trim($request->get('description'));
        $self->short_description = trim($request->get('short_description'));
        $self->show_in_first_page = @$request->has('show_in_first_page') ? 1 : 0;
        $self->has_page = @$request->has('has_page') ? 1 : 0;
        $self->double_image = @$request->has('double_image') ? 1 : 0;
        $self->thumbnail = @$request->has('thumbnail') ? 1 : 0;
        $self->images = @$request->has('double_image') ? $img : $imgs ;
        $self->after_image = $request->file('after') ? $request->file('after') : null;
        $self->before_image = $request->file('before') ? $request->file('before') : null;
        if ($request->get('has_page')) $self->url = trim(str_replace(' ', '-',@$request->get('url')));
        if ($request->get('services')) $self->services = $request->get('services');
        return $self;
    }
}
