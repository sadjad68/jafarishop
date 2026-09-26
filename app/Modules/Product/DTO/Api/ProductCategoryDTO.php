<?php

namespace App\Modules\Product\DTO\Api;

use Illuminate\Http\UploadedFile;
use App\Modules\Product\Http\Requests\Api\ProductCategoryRequest;

class ProductCategoryDTO
{
    public ?string $title;
    public ?string $description;
    public ?bool $show_in_first_page;
    public ?bool $active;
    public ?int $parent_id;
    public ?string $url;
    public ?array $seo;
    protected ?UploadedFile $image;
    protected array $originalData;

    public function __construct(array $data, ?UploadedFile $image = null)
    {
        $this->originalData = $data;
        $this->image = $image;

        $this->title = $data['title'] ?? null;
        $this->description = array_key_exists('description', $data) ? $data['description'] : null;
        $this->show_in_first_page = array_key_exists('show_in_first_page', $data) ? (bool)$data['show_in_first_page'] : null;
        $this->active = array_key_exists('active', $data) ? (bool)$data['active'] : null;
        $this->parent_id = isset($data['parent_id']) ? (int)$data['parent_id'] : null;
        $this->url = $data['url'] ?? null;
        $this->seo = $data['seo'] ?? [];
    }

    public static function fromRequest(ProductCategoryRequest $request): self
    {
        $data = $request->all();

        if ($request->has('description')) {
            $data['description'] = $request->input('description');
        }

        return new self($data, $request->file('image'));
    }

    public function getImage(): ?UploadedFile
    {
        return $this->image;
    }

    public function getSeo(): array
    {
        return $this->seo ?? [];
    }

    public function toArray(): array
    {
        $data = [];

        foreach (get_object_vars($this) as $key => $value) {
            if (in_array($key, ['originalData', 'image', 'seo'], true)) {
                continue;
            }

            if (array_key_exists($key, $this->originalData)) {
                $data[$key] = $value;
            }
        }

        return $data;
    }
}
