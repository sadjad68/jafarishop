<?php

namespace App\Modules\Product\Http\Controllers;

use App\Library\SiteHelper;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Modules\General\Helper\MakeTree;
use App\Modules\Order\Library\PaymentGatewayFactory;
use App\Modules\Product\DTO\ProductDTO;
use App\Modules\Product\DTO\TimerDTO;
use App\Modules\Product\Entities\Brand;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\ProductCategory;
use App\Modules\Product\Exports\ProductsExport;
use App\Modules\Product\Filters\ProductFilter;
use App\Modules\Product\Http\Requests\ImportRequest;
use App\Modules\Product\Http\Requests\ProductRequest;
use App\Modules\Product\Http\Requests\TimerRequest;
use App\Modules\Product\Imports\ProductsImport;
use App\Modules\Product\Jobs\SendProductNotificationJob;
use App\Modules\Product\Services\ProductService;
use App\Modules\Tag\Entities\Tag;
use App\Modules\User\Entities\User;


class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $query = Product::query();

//        dd(Session::get('product-filter'),$request->has(['filter']));
        if ($request->has(['filter'])) {
            $filters = [
                'title' => $request->get('title'),
                'url' => $request->get('url'),
                'brand_id' => $request->get('brand_id'),
                'creator_id' => $request->get('creator_id'),
                'category_id' => $request->get('category_id'),
                'stock_status' => $request->get('stock_status'),
                'show_in_first_page' => $request->get('show_in_first_page'),
            ];
            $query = app(ProductFilter::class)->apply($query, $filters);
            Session::put('product-filter', $filters);
        }
        $product = $query->orderby('id', 'DESC')
            ->select(['id', 'title', 'image', 'brand_id', 'active', 'show_in_first_page', 'created_at', 'updated_at', 'creator_id', 'url', 'discounted_price',
                'timer_active', 'start_timer', 'end_timer'])
            ->with([
                'brand' => function ($q) {
                    $q->select('id', 'title');
                },
                'creator' => function ($q) {
                    $q->select('id', 'full_name');
                },
                'categories' => function ($q) {
                    $q->select('product_categories.id', 'product_categories.title');
                }
            ])
            ->paginate(20);
        $brand = Brand::orderby('id', 'DESC')->select(['id', 'title'])->get();
        $categories = ProductCategory::orderBy('id', 'DESC')->select(['title', 'id', 'parent_id'])->get();
        if (!empty($categories)) {
            MakeTree::getData($categories);
            $categories = MakeTree::GenerateArray(['get']);
        }
        $users = User::whereHas('userTypes', function ($query) {
            $query->where('type', 'Admin');
        })->get();
        return view('admin.product.product.index', compact('product', 'brand', 'categories', 'users'));

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $brand = Brand::orderby('id', 'DESC')->select(['title', 'id'])->get();
        $categories = ProductCategory::orderBy('id', 'DESC')->select(['title', 'id', 'parent_id'])->get();
        if (!empty($categories)) {
            MakeTree::getData($categories);
            $categories = MakeTree::GenerateArray(['get']);
        }
        if ($categories == null) {
            return redirect()->route('admin.product.index')->with('error', ' ابتدا برای محصولات، دسته بندی تعریف کنید.');
        }
        $products = Product::orderByDesc('id')->select(['title', 'id'])->get();
        $related_products = [];
        $complement_products = [];
        $selected_categories = [];
        $tags = Tag::all();
        return view('admin.product.product.create', compact('brand', 'categories', 'selected_categories', 'products', 'tags', 'related_products', 'complement_products'));
    }

    public function store(ProductRequest $request)
    {
        $this->productService->create(ProductDTO::fromRequest($request));
        return redirect()->route('admin.product.index', ['page' => $request->get('fallback_page', 1)])
            ->with('success', ' محصول جدید اضافه شد.');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data = Product::findOrfail($id);
        $brand = Brand::orderby('id', 'DESC')->select(['title', 'id'])->get();
        $categories = ProductCategory::orderBy('id', 'DESC')->select(['title', 'id', 'parent_id'])->get();
        if (!empty($categories)) {
            MakeTree::getData($categories);
            $categories = MakeTree::GenerateArray(['get']);
        }
        $selected_categories = $data->category->pluck('id')->toArray();
        $products = Product::orderByDesc('id')->where('id', '<>', $id)->select(['title', 'id'])->get();
        $tags = Tag::all();
        $related_products = $data->relatedIds()->pluck('id')->toArray();
        $complement_products = $data->complementIds()->pluck('id')->toArray();
        $users = User::all();
        return view('admin.product.product.edit',
            compact('categories', 'data', 'brand', 'selected_categories', 'products', 'tags', 'related_products', 'complement_products', 'users'));

    }

    // متد برای دریافت لیست محصولات
    public function formProducts(Request $request)
    {
        $perPage = Product::orderBy('id', 'DESC')->count();

        $products = Product::query()
            ->when($request->query('query'), fn($q, $query) => $q->where('title', 'LIKE', "%$query%"))
            ->orderByDesc('id')
            ->paginate($perPage, ['id', 'title']);

        return response()->json([
            'data' => $products->items(),
            'next_page_url' => $products->nextPageUrl(),
            'has_more_pages' => $products->hasMorePages(),
            'last_page' => $products->lastPage(),
            'current_page' => $products->currentPage()
        ]);
    }

    public function update(ProductRequest $request, $id)
    {
        $this->productService->update($id, ProductDTO::fromRequest($request));

        SendProductNotificationJob::dispatch([$id]);

        $fallbackPage = $request->get('fallback_page', 1);
        $queryParams = Session::pull('product-filter', []);

        if (!empty($queryParams)) {
            $queryParams['filter'] = true;
        }

        return redirect()->route('admin.product.index', array_merge(
            ['page' => $fallbackPage],
            $queryParams
        ))->with('success', 'محصول ویرایش شد.');
    }

    public function destroy($id)
    {
        $this->productService->destroy($id);
        return Redirect::back()->with('success', 'آیتم موردنظر با موفقیت حذف شد');
    }

    public function timer(TimerRequest $request)
    {
        $this->productService->timer(TimerDTO::fromRequest($request));
        return Redirect::back()->with('success', 'تایمر موردنظر با موفقیت ویرایش شد');
    }

    public function export(Request $request)
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '360000');

        return Excel::download(new ProductsExport($request), 'product.xlsx');
    }

    public function import(ImportRequest $request)
    {
        $productImport = new ProductsImport();
        Excel::import($productImport, $request->file('excel'));
        $updatedIds = $productImport->updatedProductIds;
        if (!empty($updatedIds)) {

            SendProductNotificationJob::dispatch($updatedIds);
        }
        return Redirect::back()->with('success', 'با موفقیت اضافه شد و اطلاع‌رسانی‌ها در صف قرار گرفتند.');
    }

    public function notify(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'لطفا ابتدا وارد حساب خود شوید.'], 401);
        }
        $result = $this->productService->registerNotification($request->all());

        return response()->json($result);
    }

    // متد برای دریافت لیست دسته بندی
    public function formCategories(Request $request)
    {
        $search = $request->query('search');
        $site = SiteHelper::getInformation();
        $siteName = data_get($site, 'site_name', $request->getHost() ?: 'default');
        $cacheKey = 'product_categories_tree_' . md5((string) $siteName);

        // استفاده از کش برای ۶۰ دقیقه جهت جلوگیری از پردازش تکراری درخت
        $allTreeData = cache()->remember($cacheKey, 3600, function() {
            $categories = ProductCategory::orderBy('id', 'DESC')->get(['id', 'title', 'parent_id']);
            if ($categories->isEmpty()) return [];

            MakeTree::getData($categories);
            return MakeTree::GenerateArray(['get']);
        });

        $data = collect($allTreeData);

        // فیلتر جستجو روی آرایه کش شده (بسیار سریع‌تر از دیتابیس)
        if ($search) {
            $data = $data->filter(function($item) use ($search) {
                return mb_strpos($item['title'], $search) !== false;
            });
        }

        // شبیه‌سازی Pagination روی آرایه
        $page = $request->query('page', 1);
        $perPage = 20;
        $pagedData = $data->forPage($page, $perPage);

        return response()->json([
            'data' => $pagedData->map(fn($item) => [
                'id' => $item['id'],
                'text' => (isset($item['level']) && $item['level'] > 0 ? str_repeat('— ', $item['level']) : '') . $item['title']
            ])->values(),
            'has_more_pages' => ($page * $perPage) < $data->count()
        ]);
    }
}
