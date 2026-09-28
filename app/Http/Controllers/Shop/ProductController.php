<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\ProductVariant;
use Illuminate\Routing\Controller;
use App\Modules\Product\Http\Resources\ProductDetailCollection;
use App\Modules\Product\Services\BrandService;
use App\Modules\Product\Services\ProductCategoryService;
use App\Modules\Product\Services\ProductService;
use App\Modules\Order\Entities\Bank;
use App\Modules\Order\Services\SnappPayService;
use App\Modules\Product\Services\SpecificationService;
use App\Modules\Setting\Services\SloganService;

class ProductController extends Controller
{
    public function detailById(int $id, Request $request)
    {
        $product = Product::where('old_id', $id)->active()->with(
            'categories',
            'brand',
            'properties',
            'faqs',
            'videos',
            'images',
            'specification_values',
            'comments',
            'related',
            'complement'
        )->firstOrFail();

        return $this->renderDetail($product, $request);
    }

    public function detail($category, $url, Request $request)
    {
        $product = ProductService::findOne($url);
        if (!empty($product->old_id)) {
            return redirect('/product/' . $product->old_id, 301);
        }

        return $this->renderDetail($product, $request);
    }

    private function renderDetail($product, Request $request)
    {
        $categories = $product->categories;
        $brand = @$product->brand;
        $slogans_query = ['active'=>1];
        $slogans = SloganService::findAll($slogans_query);
        $properties = $product->properties;
        $faqs = $product->faqs;
        $videos = $product->videos;
        $images = ProductService::getProductImagesSizeSeperated($product->images);
        $original_images = ProductService::getProductImagesSizeSeperated($product->images()->doesntHave('variants')->get());
        $tags = $product->tags;
        //many-variants
        //Todo: Use Collations instead of service
        $main_specifications = ProductService::getSpfs($product);
        $variants = ProductService::getVariants($product);
            //
        $all_specification_children_ids = collect($main_specifications)
            ->flatMap(function ($main) {
                return collect($main['children'])->pluck('id');
            })
            ->values();

        //
        //specifications
        $specifications = $product->specification_active_values
            ->groupBy('parent_id')
            ->sortKeys();

        //check this
        $specification_values = SpecificationService::getFormatTextSpecificationsActive($product);
        //check this

        $comments = $product->comments;
        $rate = 0;
        if (count($comments) > 0) {
            $rate = ($product->comments->sum('rate') / count($comments));
        }

        if (count($product->related) > 0) {
            $related_products = $product->related->take(7);
        } else {
            //check this line
            $query = ['categories' => $categories->pluck('id')->toArray(),'active'=>true,'select'=>['title','id','url','image','final_price','price','stock','discounted_price','main_variant_specification_id']];
            $related_products = ProductService::findAll($query, $product['id'], 7);
        }
        $complement_products = $product->complement()->select(['products.title','products.id','products.url','products.image','products.final_price','products.price','products.stock','products.discounted_price','products.main_variant_specification_id'])->take(7)->get();
        //بررسی تب ها و در صورت نبودن نمایش نده
        $tabs = [
            'home-tab' => $product['description'] != null ,
            'profile-tab' => count($specifications)+count($specification_values) > 0 || count($tags) > 0,
            'video-tab' => count($videos) > 0,
            'faq-tab' => count($faqs) > 0,
            'contact-tab' => true
        ];

        // یافتن اولین تب معتبر
        $activeTab = array_key_first(array_filter($tabs));

        $userNotifications = ProductService::getUserNotifications($product->id);

        $snappPayActive = Bank::where('bank_type', 'snappay')->active()->exists();

//        $min_variant = $product->variants()->orderBy('final_price','ASC')->where('final_price','!=',0)->where('stock','>',0)->first();
        $min_variant=false;
        if ($request->variant) {
            $variant = $product->variants()->where('id', $request->variant)->first();
            if ($variant) {
                $min_variant = $variant;
            }
        }
        return view('pages.product-detail.index', compact('product', 'brand',
            'categories', 'comments', 'slogans', 'specifications', 'specification_values', 'properties', 'rate',
            'faqs', 'videos', 'images', 'related_products','complement_products', 'tags',
            'activeTab','tabs','main_specifications','min_variant','all_specification_children_ids','variants','original_images','userNotifications','snappPayActive'));
    }

    public function getDiscountedProducts()
    {
        $query_timer = ['timer' => true, 'paginate' => true, 'page_stock' => true, 'active' => true];
        $products = ProductService::findAll($query_timer)->paginate(12);

        return view('pages.discounted-list.index', compact('products'));
    }

    public function getAllProducts()
    {

        $query = ['page_stock' => true,'active'=>true];
        $products = ProductService::findAll($query, null, 12);
        $min_price = ProductService::prices()['min_price'];
        $max_price = ProductService::prices()['max_price'];

        //brands;
        $query_brand = ['has_products' => true,'select'=>['id','title']];
        $brands = [];
        //categories
        $query_category = ['has_products' => true,'select'=>['id','title']];
        $categories = [];

        //specifications
        $query_filter = ['is_filter' => true];
        $filters = SpecificationService::findAll($query_filter);

        $min_filter_price = \request()->priceRange ? intval(explode('_', \request()->priceRange)[0]) : $min_price;
        $max_filter_price = \request()->priceRange ? intval(explode('_', \request()->priceRange)[1]) : $max_price;
        return view('pages.all-product-list.index', compact([
            'products', 'brands', 'filters', 'max_price', 'min_price', 'max_filter_price', 'min_filter_price','categories']));
    }

    public function checkSnappPay(Request $request)
    {
        $request->validate(['price' => 'required|numeric|min:1']);

        $bank = Bank::where('bank_type', 'snappay')->active()->first();
        if (!$bank) {
            return response()->json([
                'snapp_show' => false,
                'snapp_title_message' => null,
                'snapp_description' => null,
            ]);
        }

        $snapp_data = SnappPayService::checkSnappay((int) $request->price);
        return response()->json($snapp_data);
    }

    public function notify(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'type' => 'required|in:discount,available,auction',
            'current_price' => 'nullable|numeric',
        ]);

        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'ابتدا وارد شوید'], 401);
            }

            $type = $request->type === 'auction' ? 'discount' : $request->type;

            $data = [
                'product_id' => $request->product_id,
                'product_variant_id' => $request->product_variant_id,
                'type' => $type,
                'current_price' => $request->current_price
            ];

            $result = ProductService::registerNotification($data);
            return response()->json($result);

        } catch (\Exception $e) {
            \Log::error('Notification Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }}
