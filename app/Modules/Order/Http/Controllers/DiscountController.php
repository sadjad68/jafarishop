<?php

namespace App\Modules\Order\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Modules\General\Helper\MakeTree;
use App\Modules\Order\DTO\DiscountDTO;
use App\Modules\Order\Entities\Discount;
use App\Modules\Order\Filters\DiscountFilter;
use App\Modules\Order\Http\Requests\DiscountRequest;
use App\Modules\Order\Services\DiscountService;
use App\Modules\Product\Entities\Brand;
use App\Modules\Product\Entities\ProductCategory;
use App\Modules\User\Entities\User;

class DiscountController extends Controller
{
    protected $discountService;
    public function __construct(DiscountService $discountService)
    {
        $this->discountService = $discountService;
    }
    /**
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $query = Discount::query();
        if ($request->has(['filter'])) {
            $filters = [
                'title' => $request->input('title'),
                'user_id' => $request->input('user_id'),
            ];
            $query = app(DiscountFilter::class)->apply($query, $filters);
        }
        $discounts= $query->orderByDesc('id')->paginate(20);
        return view('admin.order.discount.index', compact('discounts'));

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $user = User::all();
        $product_categories = ProductCategory::orderBy('id', 'DESC')->select(['title', 'id', 'parent_id'])->get();
        if (!empty($product_categories)) {
            MakeTree::getData($product_categories);
            $product_categories = MakeTree::GenerateArray(['get']);
        }
        $selected_categories = [];
        $brands = Brand::all();
        $selected_brands = [];
        return view('admin.order.discount.create', compact('user', 'product_categories', 'selected_categories', 'brands', 'selected_brands'));

    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(DiscountRequest $request)
    {

        $this->discountService->create(DiscountDTO::fromRequest($request));
        return redirect()->route('admin.discount.index', ['page' => $request->get('fallback_page',1)])
            ->with('success', 'روش ارسال جدید اضافه شد.');
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(int $id)
    {
        $data = Discount::findOrfail($id);
        $user = User::all();
        $product_categories = ProductCategory::orderBy('id', 'DESC')->select(['title', 'id', 'parent_id'])->get();
        if (!empty($product_categories)) {
            MakeTree::getData($product_categories);
            $product_categories = MakeTree::GenerateArray(['get']);
        }
        $selected_categories = $data->productCategories->pluck('id')->toArray();
        $brands = Brand::all();
        $selected_brands = $data->brands->pluck('id')->toArray();
        return view('admin.order.discount.edit', compact('data','user', 'product_categories', 'selected_categories', 'brands', 'selected_brands'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(DiscountRequest $request, $id)
    {
        $this->discountService->update($id, DiscountDTO::fromRequest($request));
        return redirect()->route('admin.discount.index', ['page' => $request->get('fallback_page',1)])
            ->with('success', 'روش ارسال ویرایش شد.');

    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $this->discountService->deleteOne($id);
        return Redirect::back()->with('success', 'آیتم موردنظر با موفقیت حذف شد');
    }

}
