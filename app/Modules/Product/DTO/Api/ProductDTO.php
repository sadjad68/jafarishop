<?php

namespace App\Modules\Product\DTO\Api;

use \App\Modules\General\Helper\NumberHelper;
use Illuminate\Http\Request;

class ProductDTO
{
    protected array $originalData;

    public ?string $title;
    public ?string $url;
    public ?float $price;
    public ?float $discounted_price;
    public ?float $final_price;
    public ?string $description;
    public ?bool $active;
    public ?int $brand_id;
    public ?float $weight;
    public ?int $stock;
    public ?bool $show_in_first_page;
    public ?array $categories;
    public ?array $tags;
    public ?array $related;
    public ?array $complement;
    public ?array $properties;
    public ?array $seo;


    public function __construct(array $data)
    {
        $this->originalData = $data;

        $this->title = $data['title'] ?? null;
        $this->url = $data['url'] ?? null;
        $this->price = isset($data['price']) ? (float)$data['price'] : null;
        $this->discounted_price = isset($data['discounted_price']) ? (float)$data['discounted_price'] : null;

        // فقط محاسبه کن، اگه یکی از price یا discounted_price فرستاده شده
        if (array_key_exists('price', $data) || array_key_exists('discounted_price', $data)) {
            $this->final_price = intval(NumberHelper::persian2LatinDigit($data['discounted_price'] ?? 0)) != 0
                ? intval(NumberHelper::persian2LatinDigit($data['discounted_price']))
                : intval(NumberHelper::persian2LatinDigit($data['price'] ?? 0));
        } else {
            $this->final_price = null;
        }

        $this->description = $data['description'] ?? null;
        $this->brand_id = isset($data['brand_id']) ? (int)$data['brand_id'] : null;
        $this->weight = isset($data['weight']) ? (float)$data['weight'] : null;
        $this->stock = isset($data['stock']) ? (int)$data['stock'] : null;

        $this->active = array_key_exists('active', $data) ? (bool)$data['active'] : null;
        $this->show_in_first_page = array_key_exists('show_in_first_page', $data) ? (bool)$data['show_in_first_page'] : null;

        $this->categories = $data['categories'] ?? [];
        $this->tags = $data['tags'] ?? [];
        $this->related = $data['related'] ?? [];
        $this->complement = $data['complement'] ?? [];
        $this->properties = $data['properties'] ?? [];
        $this->seo = $data['seo'] ?? [];
    }

    public static function fromRequest(Request $request): self
    {
        return new self($request->all());
    }

    public function toArray(): array
    {
        $data = [];

        foreach (get_object_vars($this) as $key => $value) {
            if ($key === 'originalData') continue;

            // فقط فیلدهایی که کاربر واقعاً فرستاده یا آرایه خالی هستن
            if (array_key_exists($key, $this->originalData) || (is_array($value) && empty($value))) {
                $data[$key] = $value;
            }
        }

        // اگر کاربر price یا discounted_price فرستاده، final_price هم مجازه بیاد
        if (array_key_exists('price', $this->originalData) || array_key_exists('discounted_price', $this->originalData)) {
            $data['final_price'] = $this->final_price;
        }

        return $data;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function getPriceFormula(): ?string
    {
        return $this->originalData['price_formula'] ?? null;
    }
}
