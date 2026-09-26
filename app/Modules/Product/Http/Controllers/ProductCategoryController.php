<?php

namespace App\Modules\Product\Http\Controllers;

use App\Modules\General\Helper\CacheHelper;
use App\Modules\Blog\DTO\BlogCategoryDTO;
use App\Modules\Blog\Entities\BlogCategory;
use App\Modules\Blog\Filters\BlogCategoryFilter;
use App\Modules\Blog\Http\Requests\BlogCategoryRequest;
use App\Modules\Blog\Services\BlogCategoryService;
use App\Modules\Product\DTO\ProductCategoryDTO;
use App\Modules\Product\Entities\ProductCategory;
use App\Modules\Product\Filters\ProductCategoryFilter;
use App\Modules\Product\Http\Requests\ProductCategoryRequest;
use App\Modules\Product\Services\ProductCategoryService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Modules\Product\Entities\Specification;

class ProductCategoryController extends Controller
{
    protected $productCategoryService;

    public function __construct(ProductCategoryService $productCategoryService)
    {
        $this->productCategoryService = $productCategoryService;
    }

    /**
     * /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $query = ProductCategory::query();
        if ($request->has(['filter'])) {
            $filters = [
                'title' => $request->get('title'),
                'url' => $request->get('url'),
                'show_in_first_page' => $request->get('show_in_first_page'),
            ];
            $query = app(ProductCategoryFilter::class)->apply($query, $filters);
            $product_category = $query->orderByDesc('id')->select(['id', 'title', 'url', 'active','show_in_site', 'parent_id', 'show_in_first_page', 'image','created_at', 'updated_at'])->paginate(15);
        } else {
            $product_category = $query->orderByDesc('id')->select(['id', 'title', 'url', 'active','show_in_site', 'parent_id', 'show_in_first_page', 'image','created_at', 'updated_at'])->with([
                'children' => function ($q) {
                    $q->orderByDesc('id')->select('id', 'title', 'url', 'parent_id', 'sort', 'image','created_at', 'updated_at');
                }
            ])->get();
            $product_category = $this->productCategoryService->formatProductCategories($product_category, 15);
        }

        return view('admin.product.product-category.index', compact('product_category'));

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $product_categories = $this->productCategoryService->findAll();
        $specifications = Specification::whereNull("parent_id")->where("type", 'select')->select("id", 'title', 'parent_id')->orderBy('id', "desc")->get();
        return view('admin.product.product-category.create', compact('product_categories', 'specifications'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(ProductCategoryRequest $request)
    {
        $message = $this->productCategoryService->create(ProductCategoryDTO::fromRequest($request));
        CacheHelper::clearCache();

        return redirect()->route('admin.product-category.index', ['page' => $request->get('fallback_page', 1)])
            ->with('success', 'دسته بندی محصول جدید اضافه شد.')
            ->with('info', $message); // پیام هشدار اضافه می‌شود
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(int $id)
    {
        $data = ProductCategory::findOrfail($id);
        $product_categories = $this->productCategoryService->findAll();
        $specifications = Specification::whereNull("parent_id")->where("type", 'select')->select("id", 'title', 'parent_id')->orderBy('id', "desc")->get();
        return view('admin.product.product-category.edit', compact('data', 'product_categories', 'specifications'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(ProductCategoryRequest $request, $id)
    {
        $message = $this->productCategoryService->update($id, ProductCategoryDTO::fromRequest($request));
        CacheHelper::clearCache();
        return redirect()->route('admin.product-category.index', ['page' => $request->get('fallback_page', 1)])
            ->with('success', 'دسته بندی محصول ویرایش شد.')
            ->with('info', $message); // پیام هشدار اضافه می‌شود

    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $this->productCategoryService->deleteOne($id);
        CacheHelper::clearCache();
        return Redirect::back()->with('success', 'آیتم موردنظر با موفقیت حذف شد');
    }

    public function deleteRoot($id)
    {
        $this->productCategoryService->deleteRoot($id);
        CacheHelper::clearCache();
        return Redirect::back()->with('success', 'آیتم موردنظر با موفقیت حذف شد');
    }

    //sort
    public function sort(int $parent_id = null)
    {
        if ($parent_id == null) {
            $data = null;
            $product_categories = $this->productCategoryService->findAll(['list' => true]);
        } else {
            $data = ProductCategory::findOrfail($parent_id);
            $product_categories = $this->productCategoryService->findAll(['parent_id' => $parent_id], false);
        }

        return view('admin.product.product-category.sort', compact('data', 'product_categories'));

    }

    public function updateSort(Request $request, int $parent_id = null)
    {
        foreach ($request->order as $key => $row) {
            $category = ProductCategory::findOrFail($row);
            $category->sort = $key + 1;
            $category->save();
        }
        CacheHelper::clearCache();
        echo 'با موفقیت ذخیره شد.';

    }

    public function getSpecification($type_id)
    {
        $type = Specification::where("parent_id", null)->findOrFail($type_id);
        $specifications = Specification::where("parent_id", $type->id)->select('id', 'title', 'parent_id')->get();
        return response()->json($specifications);
    }
}
