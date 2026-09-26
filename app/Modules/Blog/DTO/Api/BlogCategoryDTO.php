<?php

namespace App\Modules\Blog\DTO\Api;

use App\Modules\Blog\Http\Requests\Api\BlogCategoryRequest;
use App\Modules\General\Traits\HasFilteredArrayTrait;

class BlogCategoryDTO
{
    use HasFilteredArrayTrait;
    protected array $originalData;

    public ?string $title;
    public ?string $description;
    public ?bool $status;
    public ?int $parent_id;
    public ?string $url;
    public ?string $title_seo;
    public ?string $description_seo;
    public ?array $seo;


    public function __construct(array $data)
    {
        $this->originalData = $data;

        $this->title = $data['title'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->status = array_key_exists('status', $data) ? (bool)$data['status'] : null;
        $this->parent_id = isset($data['parent_id']) ? (int)$data['parent_id'] : null;
        $this->url = $data['url'] ?? null;
        $this->seo = $data['seo'] ?? [];

    }

    public static function fromRequest(BlogCategoryRequest $request): self
    {
        return new self($request->all());
    }

}
