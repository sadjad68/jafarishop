<?php

namespace App\Modules\Product\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Modules\Product\DTO\ProductDTO;
use App\Modules\Product\DTO\TimerDTO;
use App\Modules\Product\Entities\ProductNotification;
use App\Modules\Product\Entities\Product;
use Carbon\Carbon;
use App\Modules\Product\Entities\ProductCategory;
use App\Modules\Product\Entities\ProductVariant;
use App\Modules\Product\Entities\SaleNotification;
use App\Modules\Product\Entities\Specification;

class ProductService
{


    public function create(ProductDTO $productDTO)
    {
        $product = Product::create([
            'title' => $productDTO->getTitle(),
            'url' => $productDTO->getUrl(),
            'description' => $productDTO->getDescription(),
            'brand_id' => $productDTO->getBrandId(),
            'creator_id' => Auth::id(),
            'active' => $productDTO->getActive(),
//            'unstable_price' => $productDTO->getUnstablePrice(),
            'price' => $productDTO->getPrice(),
            'discounted_price' => $productDTO->getDiscountedPrice(),
            'final_price' => $productDTO->getFinalPrice(),
            'show_in_first_page' => $productDTO->isShowInFirstPage(),
            'stock' => $productDTO->getStock(),
            'weight' => $productDTO->getWeight() ?? 0,
            'price_formula' => $productDTO->getPriceFormula(),
        ]);
        $product->categories()->attach($productDTO->getCategories());
        $product->related()->attach($productDTO->getProducts(), ['type' => 'related']);
        $product->complement()->attach($productDTO->getComplement(), ['type' => 'complement']);
        $product->tags()->attach($productDTO->getTags());
        if ($productDTO->getPriceFormula() != null) {
            try {
                $priceFormula = $productDTO->getPriceFormula();
                $class = 'App\\Modules\\Product\\Library\\' . $priceFormula;
                $response = new $class($productDTO->getWeight());
                $price = $response->fetchData();
                $product->updtae([[
                    'price' => $price,
                    'discounted_price' => 0,
                    'final_price' => $price,
                ]]);
            } catch (\Exception $e) {
                \Log::info("خطا در دریافت اطلاعات: " . $e->getMessage());
            }
        }

    }

    public function update(int $id, ProductDTO $productDTO)
    {
        $product = Product::findOrfail($id);
        $product->update([
            'title' => $productDTO->getTitle(),
            'url' => $productDTO->getUrl(),
            'description' => $productDTO->getDescription(),
            'brand_id' => $productDTO->getBrandId(),
            'active' => $productDTO->getActive(),
//            'unstable_price' => $productDTO->getUnstablePrice(),
            'price' => $productDTO->getPrice(),
            'discounted_price' => $productDTO->getDiscountedPrice(),
            'final_price' => $productDTO->getFinalPrice(),
            'show_in_first_page' => $productDTO->isShowInFirstPage(),
            'stock' => $productDTO->getStock(),
            'weight' => $productDTO->getWeight() ?? 0,
            'price_formula' => $productDTO->getPriceFormula(),
        ]);
        $product->categories()->sync($productDTO->getCategories());
        $product->related()->syncWithPivotValues($productDTO->getProducts(), ['type' => 'related']);
        $product->complement()->syncWithPivotValues($productDTO->getComplement(), ['type' => 'complement']);
        $product->tags()->sync($productDTO->getTags());

        if ($productDTO->getPriceFormula() != null) {
            try {
                $priceFormula = $productDTO->getPriceFormula();
                $class = 'App\\Modules\\Product\\Library\\' . $priceFormula;
                if (count($product->variants) == 0) {
                    $response = new $class($productDTO->getWeight());
                    $price = $response->fetchData();
                    $product->update([
                        'price' => $price,
                        'discounted_price' => 0,
                        'final_price' => $price,
                    ]);
                } else {
                    foreach ($product->variants as $variant) {
                        $response = new $class($variant->weight);
                        $price = $response->fetchData();
                        $variant->update([[
                            'price' => $price,
                            'discounted_price' => 0,
                            'final_price' => $price,
                        ]]);
                    }
                    $sum_stock = $product->variants()->orderBy('final_price', 'ASC')->sum('stock');
                    $minimum_price_variant = $product->variants()
                        ->orderByRaw('CAST(final_price AS UNSIGNED) ASC')
                        ->where('stock', '<>', '0')->where('final_price', '<>', '0')
                        ->first();
                    $product->update(
                        [
                            'price' => $minimum_price_variant ? $minimum_price_variant['price'] : 0,
                            'discounted_price' => $minimum_price_variant ? $minimum_price_variant['discounted_price'] : 0,
                            'final_price' => $minimum_price_variant ? $minimum_price_variant['final_price'] : 0,
                            'stock' => $sum_stock,
                        ]
                    );
                }


            } catch (\Exception $e) {
                \Log::info("خطا در دریافت اطلاعات: " . $e->getMessage());
            }
        }

    }

    public function timer(TimerDTO $timerDTO)
    {
        if ($timerDTO->getStartTimer() > $timerDTO->getTimerDate()) {
            return \redirect()->back()->with('error', 'تاریخ آغاز نباید از تاریخ پایان بزرگتر باشد');
        }

        $product = Product::findOrFail($timerDTO->getProductId());
        $product->update([
            'end_timer' => $timerDTO->getTimerDate(),
            'start_timer' => $timerDTO->getStartTimer(),
            'timer_active' => $timerDTO->getTimerActive(),
        ]);
    }

    public function destroy(int $id)
    {

        Product::destroy($id);
    }

    public static function findAll($query = [], $except_id = null, $limit = null)

    {
        $products = Product::query()->withCount('variants');
        if (isset($query['categories'])) {
            $products->whereHas('categories', function ($query2) use ($query) {
                $query2->whereIn("product_category_id", $query['categories']);
            });
        }
        if (isset($query['category_filter'])) {
            ProductService::filterByCategoriesAndSpecs($products, $query['category_filter'] ?? null);
        }
        if ($except_id) {
            $products->where('id', '<>', $except_id);
        }
        if (isset($query['brand'])) {
            $products->where('brand_id', $query['brand']);
        }
        if (isset($query['category'])) {
            $products->whereHas('categories', function ($query2) use ($query) {
                $query2->where("product_category_id", $query['specific_id']);
            });
        }
        if (isset($query['first_page'])) {
            $products->firstPage();
        }
        if (isset($query['price_formula'])) {
            $products->whereNotNull('price_formula');
        }
        if (isset($query['active'])) {
            $products->active();
        }
        if (isset($query['select'])) {

            $products->select($query['select']);
        }
        if (isset($query['timer'])) {
            $current_time = Carbon::now()->timezone('Asia/Tehran');

            $products->where(function ($query) use ($current_time) {
                $query->whereNotNull('discounted_price')
                    ->orWhere('discounted_price', '<>', 0);
            })->where('timer_active', '1')
                ->where('start_timer', '<', $current_time)
                ->where('end_timer', '>', $current_time);
        }

        if (isset($query['related'])) {
            $products->whereHas('related', function ($query2) use ($query) {
                $query2->whereIn("related_product_id", $query['related']);
            });
        }

        if (isset($query['complement'])) {
            $products->whereHas('complement', function ($query2) use ($query) {
                $query2->whereIn("related_product_id", $query['complement']);
            });
        }
        if (isset($query['page_stock'])) {
            $products->orderByRaw('stock > 0 DESC')
                ->orderByRaw('final_price > 0 DESC');
        }
        // فیلتر بازه قیمتی انتخاب‌شده توسط کاربر (مثلاً از request یا API)
        if (isset($query['min_price'], $query['max_price']) && is_numeric($query['min_price']) && is_numeric($query['max_price'])) {
            $products->whereBetween('final_price', [(int) $query['min_price'], (int) $query['max_price']]);
        }
        if (isset($query['paginate'])) {
            return $products->orderby('id', 'DESC');
        }
        if ($limit != null) {
            return $products->orderby('id', 'DESC')->take($limit)->get();
        } else {
            return $products->orderby('id', 'DESC')->get();
        }
    }

    public static function findOne($url)
    {
        return Product::where('url', $url)->active()->with('categories', 'brand', 'properties', 'faqs', 'videos', 'images', 'specification_values', 'comments', 'related', 'complement')->firstOrFail();
    }

    public static function getProductImagesSizeSeperated($images)
    {
        $images_format = [];
        foreach ($images as $image) {
            $images_format[] = [
                'id' => $image->id,
                'variants' => $image->variants->pluck('id')->toArray(),
                'image_big' => $image->getImage('big'),
                'image_small' => $image->getImage('small'),
                'image_medium' => $image->getImage('medium'),
            ];
        }
        return $images_format;
    }

    public static function prices($query = [])
    {
        $min_price = Product::orderby('stock', 'DESC')->min('final_price');
        $max_price = Product::orderby('stock', 'DESC')->max('final_price');
        if (isset($query['brand'])) {
            $min_price = Product::orderby('stock', 'DESC')->where('brand_id', $query['brand'])->min('final_price');
            $max_price = Product::orderby('stock', 'DESC')->where('brand_id', $query['brand'])->max('final_price');
        }
        if (isset($query['categories'])) {
            $min_price = Product::orderby('stock', 'DESC')->whereHas('categories', function ($query3) use ($query) {
                $query3->whereIn("product_category_id", $query['categories']);
            })->min('final_price');
            $max_price = Product::orderby('stock', 'DESC')->whereHas('categories', function ($query3) use ($query) {
                $query3->whereIn("product_category_id", $query['categories']);
            })->max('final_price');
        }


        return [
            'min_price' => intval($min_price),
            'max_price' => intval($max_price),
        ];
    }

    public function findInFirstPage($paginate = 12)
    {
        return Product::firstPage()->take($paginate)->get();
    }

    public static function findPaginate($perPage = 50, $page = 1)
    {
        $query = Product::query()->active();
        $query->select('title', 'id', 'url', 'stock', 'price', 'final_price', 'image');
        $products = $query->paginate($perPage, ['*'], 'page', $page);
        $products->getCollection()->transform(function ($product) {
            $product->Id = (string)$product->id;
            $product->is_available = $product->stock > 0;
            $product->old_price = $product->price;
            $product->url = \App\Library\SiteUrl::product($product);
            $product->price = $product->final_price;
            $product->category = @$product->categories['0']['title'] ? $product->categories['0']['title'] : 'ندارد';
            unset($product->stock, $product->categories, $product->final_price, $product->id);
            $product->image = $product->getImage();
            return $product;
        });
        return $products;
    }

    public static function getFormattedZarebinProducts($perPage = 20, $page = 1)
    {
        $query = Product::query()->active();
        $query->select('title', 'id', 'url', 'stock', 'price', 'final_price', 'discounted_price', 'image')
            ->with([
                'categories',
                'images.variants',   // بارگذاری یکجای variants تصاویر (حذف N+1)
                'specification_values.parent',
                'seo',               // بارگذاری یکجای seo_metas (حذف N+1 برای seoDescription)
            ]);

        $products = $query->paginate($perPage, ['*'], 'page', $page);

        $products->getCollection()->transform(function ($product) {
            $categories = $product->categories->pluck('title')->toArray();

            $imageLinks = [];
            $images = self::getProductImagesSizeSeperated($product->images);
            foreach ($images as $row) {
                $imageLinks[] = $row['image_medium'];
            }

            return [
                "title" => $product->title,
                "id" => $product->id,
                "current_price" => intval($product['discounted_price']) != 0
                    ? (string)intval($product['discounted_price'])
                    : (string)intval($product['price']),
                "old_price" => intval($product['discounted_price']) != 0
                    ? (string)intval($product['price'])
                    : null,
                "availability" => intval($product->stock) != 0 ? 'instock' : 'outofstock',
                "categories" => $categories,
                "image_link" => $product->getImage(),
                "image_links" => $imageLinks,
                "page_url" => \App\Library\SiteUrl::product($product),
                "short_desc" => $product->seoDescription,
                "spec" => self::getSpecifications($product),
            ];
        });

        return $products;
    }

    private static function getSpecifications($product)
    {
        $specifications = $product->specification_values->groupBy('parent_id');
        $specification_values = SpecificationService::getFormatTextSpecifications($product);

        $specArray = [];

        foreach ($specifications as $specificationGroup) {
            foreach ($specificationGroup as $row) {
                $specArray[$row->parent->title ?? ''] = $row['title'];
            }
        }

        foreach ($specification_values as $specificationGroup) {
            foreach ($specificationGroup as $row) {
                $specArray[$row['specification']] = $row['value'];
            }
        }

        return $specArray;
    }

    public static function filterByCategoriesAndSpecs3($query, array $categoryIds = [])
    {
        $query->whereHas('categories', function ($query2) use ($categoryIds) {
            $query2->whereIn("product_category_id", $categoryIds);
        });
    }
//    public static function filterByCategoriesAndSpecs2($query, array $categoryIds = [])
//    {
//        if (!count($categoryIds)) {
//            return $query;
//        }
//
//        // دسته‌های آخر
//        $lastLevelCategories = ProductCategory::whereIn('id', $categoryIds)
//            ->whereDoesntHave('children')
//            ->get();
//
//        $listProductIds = [];
//
//        foreach ($lastLevelCategories as $cat) {
//            $specGroups = $cat->specificationConditions()
//                ->get()
//                ->groupBy('parent_id');
//
//            if ($specGroups->count()) {
//                $ids = Product::where(function ($q) use ($specGroups) {
//                    foreach ($specGroups as $parentId => $specItems) {
//                        $specIds = $specItems->pluck('id')->toArray();
//                        $q->whereHas('specifications', function ($subQ) use ($specIds) {
//                            $subQ->whereIn('specification_id', $specIds);
//                        });
//                    }
//                })->pluck('id')->toArray();
//
//                $listProductIds = array_merge($listProductIds, $ids);
//            }
//        }
//
//        $listProductIds = array_unique($listProductIds);
//
//        // اعمال شرط روی query که از بیرون پاس شده
//        $query->where(function($q) use ($categoryIds, $listProductIds) {
//            if (count($listProductIds)) {
//                $cloneQ = clone $q;
//
//                $productIds = $cloneQ->whereHas('categories', function ($query2) use ($categoryIds) {
//                    $query2->whereIn('product_category_id', $categoryIds);
//                })->get()->pluck('id')->toArray();
//
//                $listProductIds = array_merge($listProductIds, $productIds);
//
//                $q->whereIn('id', $listProductIds);
//            } else {
//                $q->whereHas('categories', function ($query2) use ($categoryIds) {
//                    $query2->whereIn('product_category_id', $categoryIds);
//                });
//            }
//        });
//
//        return $query;
//    }


//    public static function filterByCategoriesAndSpecs($query, array $categoryIds = [])
//    {
//        if (empty($categoryIds)) {
//            return $query;
//        }
//
//        return $query->whereHas('categories', function ($q) use ($categoryIds) {
//            $q->whereIn('product_categories.id', $categoryIds);
//        });
//    }
    public static function filterByCategoriesAndSpecs($query, ProductCategory $main_category)
    {
        if (empty($main_category)) {
            return $query;
        }

        $specGroups = $main_category->specificationConditions()
            ->get()
            ->groupBy('parent_id');

        $children_ids = ProductCategoryService::getAllCategoryIdsRecursive($main_category);

        // آیا حداقل یک محصول به این دسته یا زیردسته‌ها منتسب است؟
        $hasProductsInCategory = Product::whereHas('categories', function ($q) use ($children_ids) {
            $q->whereIn('product_category_id', $children_ids);
        })->exists();

        $hasSpec = $specGroups->count() > 0;
        $hasPriceRange = (int) $main_category->have_price_range === 1;

        // حالت فقط بازه قیمتی: هیچ مشخصه‌ای نیست و واقعاً هیچ محصولی به این دسته (و زیردسته‌ها) منتسب نیست
        if ($hasPriceRange && !$hasSpec && !$hasProductsInCategory) {
            $query->whereBetween(
                'final_price',
                [(int) $main_category->min_price, (int) $main_category->max_price]
            );
            return $query;
        }

        // حالت عادی: فیلتر بر اساس مشخصه و/یا دسته
        $query->where(function ($outer) use ($specGroups, $children_ids) {
            if ($specGroups->count()) {
                $outer->where(function ($q) use ($specGroups) {
                    foreach ($specGroups as $parentId => $specItems) {
                        $specIds = $specItems->pluck('id')->toArray();
                        $q->whereHas('specifications', function ($subQ) use ($specIds) {
                            $subQ->whereIn('specification_id', $specIds);
                        });
                    }
                });
            }
            if (!empty($children_ids)) {
                $outer->orWhereHas('categories', function ($q) use ($children_ids) {
                    $q->whereIn('product_categories.id', $children_ids);
                });
            }
        });

        if ($hasPriceRange) {
            $query->whereBetween(
                'final_price',
                [(int) $main_category->min_price, (int) $main_category->max_price]
            );
        }

        return $query;
    }

    public static function getSpfs($product)
    {
        $specificationsByMain = [];
        $specificationIds = $product->variants
            ->flatMap(function ($variant) {
                return $variant->specifications->pluck('id');
            })
            ->values();

        foreach ($product->main_specifications as $main_specification) {
            $childSpecifications = Specification::whereIn('id', $specificationIds)
                ->where('parent_id', $main_specification->id)
                ->get(['id', 'title', 'color_code'])
                ->unique('id')
                ->values();

            if ($childSpecifications->isNotEmpty()) {
                $specificationsByMain[] = [
                    'main_id' => $main_specification->id,
                    'main_title' => $main_specification->title,
                    'main_is_color' => $main_specification->is_color,
                    'children' => $childSpecifications->map(function ($spec) use ($product) {
                        $relatedVariants = $product->variants
                            ->filter(function ($variant) use ($spec) {
                                return $variant->specifications->pluck('id')->contains($spec->id);
                            });

                        $mappedVariants = $relatedVariants->map(function ($variant) {
                            $allSpecIds = $variant->specifications->pluck('id')->toArray();
                            return [
                                'id' => $variant->id,
                                'product_id' => $variant->product_id,
                                'price' => $variant->price,
                                'weight' => $variant->weight,
                                'discounted_price' => $variant->discounted_price,
                                'final_price' => $variant->final_price,
                                'stock' => $variant->stock,
                                'price_affective' => $variant->price_affective,
                                'color_code' => $variant->color_code,
                                // فیلد ضروری برای Vue
                                'specification_ids' => $allSpecIds,
                                'images' => self::getProductImagesSizeSeperated($variant->images)
                            ];
                        })->values();

                        return [
                            'id' => $spec->id,
                            'title' => $spec->title,
                            'color_code' => $spec->color_code == null || $spec->color_code == 'undefined' ? '#000000' : $spec->color_code,
                            'variants' => $mappedVariants,
                        ];
                    })->values(),
                ];
            }
        }
        return $specificationsByMain;
    }


    public static function getVariants($product)
    {
        $variants = [];

        foreach ($product->variants()->with('specifications')->orderBy('final_price')->orderBy('stock')->get() as $variant) {
            {
                $specifications = [];

                foreach ($variant->specifications as $spec) {
                    $mainSpecificationId = $spec->parent_id ?? $variant->specification_parent_id;

                    if (!$mainSpecificationId) {
                        continue;
                    }

                    $specifications[] = [
                        'specification_value_id' => $spec->id,
                        'main_specification_id' => $mainSpecificationId,
                    ];
                }

                $variants[] = [
                    'id' => $variant->id,
                    'product_id' => $variant->product_id,
                    'price' => $variant->price,
                    'weight' => $variant->weight,
                    'discounted_price' => $variant->discounted_price,
                    'final_price' => $variant->final_price,
                    'stock' => $variant->stock,
                    'price_affective' => $variant->price_affective,
                    'specifications' => $specifications,
                    'color_code' => $variant->color_code,
                    'images' => self::getProductImagesSizeSeperated($variant->images)
                ];
            }
        }
        return $variants;
    }

    public static function registerNotification(array $data): array
    {
        if (!Auth::check()) {
            return ['success' => false, 'message' => 'ابتدا وارد سایت شوید.'];
        }

        $user = Auth::user();
        $productId = $data['product_id'];
        $variantId = $data['product_variant_id'] ?? null;
        $type = $data['type'];

        if (!in_array($type, ProductNotification::TYPES)) {
            return ['success' => false, 'message' => 'نوع درخواست معتبر نیست.'];
        }

        $conditions = [
            'user_id' => $user->id,
            'product_id' => $productId,
            'product_variant_id' => $variantId,
            'type' => $type,
            'is_sent' => false,
        ];

        $existing = ProductNotification::where($conditions)->first();

        if ($existing) {
            $existing->delete();
            $action = 'removed';
            $message = $type === ProductNotification::TYPE_AVAILABILITY
                ? 'اطلاع‌رسانی موجودی لغو شد.'
                : 'اطلاع‌رسانی حراج لغو شد.';
        } else {
            ProductNotification::create($conditions);
            $action = 'added';
            $message = $type === ProductNotification::TYPE_AVAILABILITY
                ? 'اطلاع‌رسانی موجودی ثبت شد.'
                : 'اطلاع‌رسانی حراج ثبت شد.';
        }

        return [
            'success' => true,
            'action' => $action,
            'message' => $message
        ];
    }

    public static function getUserNotifications(int $productId): array
    {
        $userNotifications = [
            'available' => [],
            'discount' => []
        ];

        if (!Auth::check()) {
            return $userNotifications;
        }

        $notifications = ProductNotification::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->where('is_sent', false)
            ->get();

        foreach ($notifications as $notif) {
            $key = $notif->product_variant_id ?? $productId;

            if ($notif->type === ProductNotification::TYPE_AVAILABILITY) {
                $userNotifications['available'][] = $key;
            } elseif ($notif->type === ProductNotification::TYPE_DISCOUNT) {
                $userNotifications['discount'][] = $key;
            }
        }

        return $userNotifications;
    }
}
