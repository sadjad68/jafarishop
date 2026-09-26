<?php

namespace App\Modules\Blog\DTO;

use App\Modules\Blog\Entities\Blog;
use App\Modules\Blog\Http\Requests\BlogRequest;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;

class BlogDTO
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
    protected array $services = [];

    public function getServices(): array
    {
        return $this->services;
    }
    protected int|null $parent_id;
    public function getParentId(): ?int
    {
        return $this->parent_id;
    }
    protected string|null $description;
    public function getDescription(): ?string
    {
        return $this->description;
    }
    protected bool $call_to_action;
    public function showCallToAction(): bool
    {
        return $this->call_to_action;
    }
    protected bool $show_in_first_page;
    public function showFirstPage(): bool
    {
        return $this->show_in_first_page;
    }
    protected string|null $author;
    public function getAuthor(): string|null
    {
        return $this->author;
    }
    protected string|null $publish_date;

    public function getPublishDate(): string|null
    {

        return $this->publish_date;
    }
    public static function fromRequest(BlogRequest $request)
    {
        $self = new self();
        $published_date = Carbon::today();
        if ($request->get('publish_date')) {

            $publish = explode('/', $request->get('publish_date'));

            $s = jmktime(0, 0, 0, $publish[1], $publish[0], $publish[2]);
            $published_date = Carbon::createFromTimestamp($s);


        }
        $self->title = $request->get('title');
        $self->description = $request->get('description');
        $self->image = $request->file('image');
        $self->parent_id = @$request->get('parent_id');
        $self->call_to_action = @$request->has('call_to_action');
        $self->show_in_first_page = @$request->has('show_in_first_page');
        $self->author = $request->get('author');
        $self->publish_date = $published_date;
        if ($request->get('services')) $self->services = $request->get('services');
        $self->url = trim(str_replace(' ', '-',@$request->get('url')));
        return $self;
    }
}
