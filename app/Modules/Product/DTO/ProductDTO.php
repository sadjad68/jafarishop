<?php

namespace App\Modules\Product\DTO;

use App\Modules\General\Helper\NumberHelper;
use App\Modules\Blog\Entities\Blog;
use App\Modules\Blog\Http\Requests\BlogRequest;
use App\Modules\Product\Http\Requests\ProductRequest;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use function App\Modules\Blog\DTO\jmktime;

class ProductDTO
{
    protected string $title;

    public function getTitle(): string
    {
        return $this->title;
    }

    protected string $url;

    public function getUrl(): string
    {
        return $this->url;
    }

    protected array $categories = [];

    public function getCategories(): array
    {
        return $this->categories;
    }

    protected array $products = [];

    public function getProducts(): array
    {
        return $this->products;
    }

    protected array $complement = [];

    public function getComplement(): array
    {
        return $this->complement;
    }

    protected array $tags = [];

    public function getTags(): array
    {
        return $this->tags;
    }

    protected int|null $brand_id;

    public function getBrandId(): ?int
    {
        return $this->brand_id;
    }

    protected string|null $description;

    public function getDescription(): ?string
    {
        return $this->description;
    }

//    protected int|null $unstable_price=0;
//
//    public function getUnstablePrice(): bool
//    {
//        if ($this->unstable_price == null) {
//            return false;
//        }
//        return $this->unstable_price;
//    }

    protected int|null $active;

    public function getActive(): bool
    {
        return $this->active;
    }

    protected string $price;

    public function getPrice(): string
    {
        return $this->price;
    }

    protected string $weight;

    public function getWeight(): string
    {
        return $this->weight;
    }

    protected string $discounted_price;

    public function getDiscountedPrice(): string
    {
        return $this->discounted_price;
    }

    protected string $final_price;

    public function getFinalPrice(): string
    {
        return $this->final_price;
    }

    protected string $show_in_first_page;

    public function isShowInFirstPage(): bool
    {
        return $this->show_in_first_page;
    }

    protected int|null $stock;

    public function getStock(): ?int
    {
        return $this->stock;
    }

    protected $price_formula;

    public function getPriceFormula(): ?string
    {
        return $this->price_formula;
    }

    public static function fromRequest(ProductRequest $request)
    {
        $self = new self();
        $self->title = $request->get('title');
        $self->description = $request->get('description');
        $self->active = $request->has('active');
        $self->unstable_price = $request->get('unstable_price');
        $self->show_in_first_page = $request->has('show_in_first_page');
        $self->brand_id = @$request->get('brand_id');
        $self->price_formula = @$request->get('price_formula');
        $self->price = intval(NumberHelper::persian2LatinDigit(@$request->get('price')));
        $self->stock = intval(NumberHelper::persian2LatinDigit(@$request->get('stock')));
        $self->weight = intval(NumberHelper::persian2LatinDigit(@$request->get('weight')));
        $self->discounted_price = intval(NumberHelper::persian2LatinDigit(@$request->get('discounted_price')));
        $self->final_price = intval(NumberHelper::persian2LatinDigit(@$request->get('discounted_price'))) != 0 ? intval(NumberHelper::persian2LatinDigit(@$request->get('discounted_price'))) : intval(NumberHelper::persian2LatinDigit(@$request->get('price')));
        if ($request->get('categories')) $self->categories = $request->get('categories');
        if ($request->get('tags')) $self->tags = $request->get('tags');
        if ($request->get('products')) $self->products = $request->get('products');
        if ($request->get('complement')) $self->complement = $request->get('complement');
        $self->url = trim(str_replace(' ', '-', @$request->get('url')));
        return $self;
    }
}
