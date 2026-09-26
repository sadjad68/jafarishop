<?php

namespace App\Modules\Setting\DTO;

use App\Modules\Setting\Http\Requests\PartialRequest;
use App\Modules\Gallery\Http\Requests\GalleryRequest;
use Illuminate\Http\UploadedFile;

class PartialDTO
{
    protected string $title;
    public function getTitle(): string
    {
        return $this->title;
    }


    protected int|null $parent_id;
    public function getParentId(): int|null
    {
        return $this->parent_id;
    }

    protected int|null $show;
    public function getShow(): int|null
    {

        return $this->show;
    }

    public static function fromRequest(PartialRequest $request)
    {
        $self = new self();
        $self->title = $request->get('title');
        $self->parent_id = @$request->get('parent_id');
        $self->show = @$request->has('show') ? 1 : 0;
        return $self;
    }
}
