<?php

namespace App\Modules\Product\Services\Api;

use App\Modules\General\Helper\NumberHelper;
use App\Modules\General\Services\ApiSeoService;
use App\Modules\Product\DTO\Api\ProductDTO;
use App\Modules\Product\DTO\Api\TimerDTO;
use App\Modules\Product\Entities\Brand;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\ProductSpecification;
use App\Modules\Product\Entities\ProductVariant;
use App\Modules\Product\Entities\Property;
use App\Modules\Product\Entities\Specification;
use App\Modules\Tag\Entities\Tag;

class ProductService
{
    public function list($request)
    {
        $query = Product::query();
        $page = @request()->get("page");

        if ($request->get("title")) {
            $query->where("title", 'like', "%" . $request->get("title") . "%");
        }
        return $query->orderBy("id", "desc")->offset(($page - 1) * 20)->limit(20)->select("id", 'url', 'title')->get();
    }
    public function find($id)
    {
        $product = Product::findOrFail($id);
        return $this->showProduct($product);
    }
    public function showProduct(Product $product)
    {
        $list = [
            "title" => $product->title,
            "description" => $product->description,
            "url" => $product->url,
            "image" => $product->image,
            "active" => $product->active,
            "price" => $product->price,
            "weight" => $product->weight,
            "discounted_price" => $product->discounted_price,
            "final_price" => $product->final_price,
            "stock" => $product->stock,
            "show_in_first_page" => $product->show_in_first_page,
            "timer_active" => $product->timer_active,
        ];
        if ($product->timer_active) {
            $list["start_timer"] = NumberHelper::persian2LatinDigit($product->start_timer ? jdate('Y/m/d - H:i', $product->start_timer) : null);
            $list["end_timer"] = NumberHelper::persian2LatinDigit($product->end_timer ? jdate('Y/m/d - H:i', $product->end_timer) : null);
        }
        if ($product->seo) {
            $list["seo"]['h1'] = @$product->seo->h1;
            $list["seo"]['title_seo'] = @$product->seo->title_seo;
            $list["seo"]['description_seo'] = @$product->seo->description_seo;
            $list["seo"]['noindex'] = @$product->seo->noindex;
        }
        if (count($product->categories ?? [])) {
            $list["categories"] = $product->categories?->pluck("title");
        }
        if ($product->brand) {
            $list["brand"] = [
                "id" => $product->brand->id,
                "title" => $product->brand->title,
            ];
        }
        if (count($product->related ?? [])) {
            $list["related"] = $product->related?->pluck("title");
        }
        if (count($product->complement ?? [])) {
            $list["complement"] = $product->complement?->pluck("title");
        }
        if (count($product->tags ?? [])) {
            $list["tags"] = $product->tags?->pluck("title");
        }
        if (count($product->properties ?? [])) {
            $list["properties"] = $product->properties?->pluck("value");
        }
        if (count($product->specifications ?? [])) {
            $list["specifications"] = [];
                foreach ($product->specifications ?? [] as $item) {
                    $list["specifications"][$item->parent->id] = [
                        'id' => $item->parent->id,
                        'title' => $item->parent->title,
                        'type' => 'select',
                    ];
                    $list["specifications"][$item->parent->id]['values'][$item->id] = $item->title;
                }
                foreach ($product->specification_vals ?? [] as $item) {
                    $list["specifications"][$item->specification_id] = [
                        'id' => $item->specification_id,
                        'title' => $item->specification->title,
                        'type' => 'text',
                    ];
                    $list["specifications"][$item->specification_id]['values'][] = $item->value;
                }

        }
        return $list;
    }
    public function showData($item){

        $data = $item->toArray();

        $data["seo"]['h1'] = @$item->seo->h1;
        $data["seo"]['title_seo'] = @$item->seo->title_seo;
        $data["seo"]['description_seo'] = @$item->seo->description_seo;
        $data["seo"]['noindex'] = @$item->seo->noindex;

        return $data;
    }
    public function store(ProductDTO $dto): array
    {
        $product = Product::create($dto->toArray());
        $this->applyPriceFormula($product, $dto);

        $this->syncRelations($product, $dto->toArray());

        return $this->showData($product);
    }

    public function update(Product $product, ProductDTO $dto): array
    {
        $product->update($dto->toArray());
        $this->applyPriceFormula($product, $dto);

        $this->syncRelations($product, $dto->toArray());

        return $this->showData($product);
    }

    protected function syncRelations(Product $product, $dto): void
    {
        if (isset($dto['categories']) && $dto['categories'] != null) $product->categories()->sync($dto['categories'] ?? $product->categories());
        if (isset($dto['tags']) && $dto['tags'] != null) $product->tags()->sync($dto['tags'] ?? $product->sync());
        if (isset($dto['related']) && $dto['related'] != null) $product->related()->sync($dto['related'] ?? $product->related());
        if (isset($dto['complement']) && $dto['complement'] != null) $product->complement()->sync($dto['complement'] ?? $product->complement());
        if (isset($dto['properties']) && $dto['properties'] != null) {
            $newValues = collect($dto['properties'] ?? [])->map(fn($v) => trim($v))->unique()->toArray();

            // 1️⃣ ساخت یا پیدا کردن ویژگی‌های جدید
            $propertyIds = [];
            foreach ($newValues as $value) {
                $property = Property::firstOrCreate([
                    'product_id' => $product->id,
                    'value' => $value,
                ]);
                $propertyIds[] = $property->id;
            }

            // 2️⃣ حذف ویژگی‌هایی که الان پاس داده نشده‌ن
            $product->properties()
                ->whereNotIn('id', $propertyIds)
                ->delete();
        }
        if (count(@$dto['seo'] ?? [])) {
            ApiSeoService::set($product,@$dto['seo'] ?? []);
        }
    }

    public function applyPriceFormula(Product $product, ProductDTO $productDTO)
    {
        $formula = $productDTO->getPriceFormula();
        if (!$formula) return;

        try {
            $class = 'App\\Modules\\Product\\Library\\' . $formula;
            $calculator = new $class($productDTO->getWeight());
            $price = $calculator->fetchData();

            $product->update([
                'price' => $price,
                'discounted_price' => 0,
                'final_price' => $price,
            ]);
        } catch (\Exception $e) {
            \Log::info("خطا در محاسبه قیمت داینامیک: " . $e->getMessage());
        }
    }

    public function timer(Product $product, TimerDTO $timerDTO)
    {
        if ($timerDTO->getTimerActive()) {
            if ($timerDTO->getStartTimer() > $timerDTO->getTimerDate()) {
                return [
                    'success' => false,
                    'data' => [
                        'error' => 'تاریخ آغاز نباید از تاریخ پایان بزرگتر باشد'
                    ],
                ];
            }
            $product->update([
                'end_timer' => $timerDTO->getTimerDate(),
                'start_timer' => $timerDTO->getStartTimer(),
                'timer_active' => $timerDTO->getTimerActive(),
            ]);
        } else {
            $product->update([
                'end_timer' => null,
                'start_timer' => null,
                'timer_active' => $timerDTO->getTimerActive(),
            ]);
        }

        return [
            'success' => true,
            'data' => [
                'timer_active' => $product->timer_active,
                'start_timer' => $product->start_timer ? NumberHelper::persian2LatinDigit(jdate('Y/m/d - H:i', $product->start_timer)) : null,
                'end_timer' => $product->end_timer ? NumberHelper::persian2LatinDigit(jdate('Y/m/d - H:i', $product->end_timer)) : null,
            ]
        ];
    }
    public function getVariants(int $productId)
    {
        $product = Product::with('variants.specification')->findOrFail($productId);

        return $product->variants->map(function ($variant) {
            $item = [
                'id' => $variant->id,
                'price_affective' => $variant->price_affective,
                'stock' => $variant->stock,
                'weight' => $variant->weight,
                'price' => $variant->price,
                'discounted_price' => $variant->discounted_price,
                'final_price' => $variant->final_price,
            ];
            if (@$variant->specification?->id) {
                $item['specification'] = [
                    "id" => $variant->specification?->id,
                    "title" => $variant->specification?->title,
                ];
            }
            return $item;
        });
    }
    public function postVariants(Product $product, $list)
    {

        // ✅ لیست مشخصه‌های مجاز
        $allowedSpecifications = $product->mainVariantSpecification
            ? ($product->mainVariantSpecification->children()->pluck('id')->toArray() ?? [])
            : [];
        $listVars = [];
        foreach ($list as $variant) {
            if (!in_array($variant['specification_id'], $allowedSpecifications)) {
                \Log::info("مشخصه غیرمجاز: " . $variant['specification_id']);
                continue;
            }

            $spec = Specification::find($variant['specification_id']);
            if (!$spec) continue;

            $data = [
                'product_id' => $product->id,
                'specification_parent_id' => $spec->parent_id,
                'specification_id' => $spec->id,
                'price_affective' => intval(@$variant['price_affective'] ?? 1),
                'stock' => intval(NumberHelper::persian2LatinDigit($variant['stock'])),
                'weight' => intval(NumberHelper::persian2LatinDigit($variant['weight'])),
                'price' => intval(NumberHelper::persian2LatinDigit($variant['price'])),
                'discounted_price' => intval(NumberHelper::persian2LatinDigit($variant['discounted_price'])),
            ];

            // محاسبه نهایی قیمت
            $data['final_price'] = $data['discounted_price'] != 0
                ? $data['discounted_price']
                : $data['price'];

            // 🔁 ساخت یا آپدیت
            if (!(@$variant['variant_id'])) {
                $pro = ProductVariant::create($data);
            } else {
                $pro = ProductVariant::find($variant['variant_id']);
                if ($pro) $pro->update($data);
            }
            $listVars[] = $pro;
        }

        // 🧮 بروزرسانی وضعیت کلی محصول
        $sum_stock = $product->variants()->sum('stock');
        $minimum_price_variant = $product->variants()
            ->where('stock', '>', 0)
            ->where('final_price', '>', 0)
            ->orderBy('final_price', 'ASC')
            ->first();
        $product->update([
            'price' => $minimum_price_variant->price ?? 0,
            'discounted_price' => $minimum_price_variant->discounted_price ?? 0,
            'final_price' => $minimum_price_variant->final_price ?? 0,
            'stock' => $sum_stock,
        ]);
        return $listVars;
    }
    function getAllowedSpecifications(Product $product, array $values, $type)
    {
        // دسته‌های محصول
        $categoryIds = $product->categories->pluck('id')->toArray();

        if ($type == 'select') {
            $specs = Specification::whereIn('id', $values)->with('categories')->get();
            $specs = $specs->filter(function($spec) {
                return $spec->parent && $spec->parent->type === 'select';
            });
        }else{
            $specs = Specification::whereIn('id', $values)->where("type",'text')->with('categories')->get();
        }
        // فیلتر مشخصه‌های مجاز
        $allowed = $specs->filter(function ($spec) use ($categoryIds,$type) {

            if ($type == 'select') {

                $hasCategory = $spec->parent->categories
                    ->pluck('id')
                    ->intersect($categoryIds)
                    ->isNotEmpty();

                $isGlobal = $spec->parent->categories->isEmpty();
            }else{

                $hasCategory = $spec->categories
                    ->pluck('id')
                    ->intersect($categoryIds)
                    ->isNotEmpty();

                $isGlobal = $spec->categories->isEmpty();
            }

            return $hasCategory || $isGlobal;
        });
        return $allowed;
    }
    function specificationSelect(Product $product, null|array $values)
    {
        $specifications = $this->getAllowedSpecifications($product, $values, 'select');

        $proIds = [];
        foreach ($specifications as $item) {
            $pro = ProductSpecification::firstOrCreate(
                [
                    'product_id' => $product->id,
                    'specification_id' => $item->id,
                ],
                [
                    'parent_id' => $item->parent_id ?? null,
                ]
            );
            $proIds[] = $pro->id;
        }
        ProductSpecification::where([
            'product_id' => $product->id,
        ])->whereNull('value')->whereNotNull('parent_id')->whereNotIn('id',$proIds)->delete();

        return $specifications->pluck("title");
    }
    function specificationText(Product $product, null|array $values)
    {
        $ids = array_keys($values);
        $specifications = $this->getAllowedSpecifications($product, $ids, 'text');
        $specifications = $specifications->where('type', 'text');
        $titles = [];
        foreach ($specifications as $item) {
            $valueSpec = @$values[$item->id];
            $valueSpec = is_array($valueSpec) ? $valueSpec : [$valueSpec];
            if (count($valueSpec)) {
                $proIds = [];
                foreach ($valueSpec as $val) {
                    if (strlen($val ?? '')) {
                        $pro = ProductSpecification::firstOrCreate([
                            'value' => $val,
                            'specification_id' =>$item->id,
                            'product_id' => $product->id,
                        ]);
                        $titles[] = $val;
                        $proIds[] = $pro->id;
                    }
                }
                ProductSpecification::where([
                    'specification_id' =>$item->id,
                    'product_id' => $product->id,
                ])->whereNull("parent_id")->whereNotIn('id',$proIds)->delete();
            }
        }
        return $titles;
    }
}
