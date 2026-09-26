<?php

namespace App\Modules\Blog\DTO;

use App\Modules\Blog\Entities\BlogCategory;
use App\Modules\Blog\Http\Requests\BlogCategoryRequest;
use Illuminate\Http\UploadedFile;

class BlogCategoryDTO
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
    protected string $type;
    public function getType(): string
    {
        return $this->type;
    }
    protected string|null $description;
    public function getDescription(): ?string
    {
        return $this->description;
    }
    protected int|null $parent_id;
    public function getParentId(): ?int
    {
        return $this->parent_id;
    }
    protected bool $status;
    public function getStatus(): bool
    {
        return $this->status;
    }
    public static function fromRequest(BlogCategoryRequest $request)
    {
        $self = new self();
        $self->title = $request->get('title');
        $self->description = $request->get('description');
        $self->image = $request->file('image');
        $self->parent_id = @$request->get('parent_id');
        $self->status = @$request->has('status');
        $self->url = trim(str_replace(' ', '-',@$request->get('url')));
        $self->type = in_array($request->get('type'), [BlogCategory::TYPE_TEXT, BlogCategory::TYPE_VIDEO], true)
            ? $request->get('type')
            : BlogCategory::TYPE_TEXT;
        return $self;
    }
}
