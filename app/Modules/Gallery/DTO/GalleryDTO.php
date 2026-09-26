<?php

namespace App\Modules\Gallery\DTO;

use App\Modules\Gallery\Http\Requests\GalleryRequest;
use Illuminate\Http\UploadedFile;

class GalleryDTO
{
    protected string $title;
    public function getTitle(): string
    {
        return $this->title;
    }

    protected UploadedFile|null $file;
    public function getFile(): UploadedFile|null
    {
        return $this->file;
    }

    protected int|null $parent_id;
    public function getParentId(): int|null
    {
        return $this->parent_id;
    }

    protected int|null $show_in_first_page;
    public function getShowInFirstPage(): int|null
    {

        return $this->show_in_first_page;
    }
    protected string|null $description;
    public function getDescription(): string|null
    {
        return $this->description;
    }

    public static function fromRequest(GalleryRequest $request)
    {
        $self = new self();
        $self->title = $request->get('title');
        $self->file = $request->file('file');
        $self->parent_id = @$request->get('parent_id');
        $self->description = $request->get('description');
        $self->show_in_first_page = @$request->has('show_in_first_page') ? 1 : 0;
        return $self;
    }
}
