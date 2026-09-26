<?php

namespace App\Modules\Product\Http\Controllers;


use App\Modules\Product\DTO\MainVariantDTO;
use App\Modules\Product\DTO\VariantDTO;
use App\Modules\Product\Entities\ProductVariant;
use App\Modules\Product\Http\Requests\MainVariantRequest;
use App\Modules\Product\Http\Requests\VariantRequest;
use App\Modules\Product\Services\VariantService;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Services\SpfService;
use App\Modules\Product\Entities\Specification;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;

class VariantController extends Controller
{
    protected $videoFaqService;
    protected $spfService;

    public function __construct(
        SpfService     $spfService,
        VariantService $variantService,
    )
    {
        $this->spfService = $spfService;
        $this->variantService = $variantService;

    }

    /**
     * /**
     * Display a listing of the resource.
     * @return Renderable
     */

    public function index($id)
    {
        $product = Product::findOrFail($id);
        $generalSpecifications = Specification::with(['children','product_values'])
            ->orderByDesc('id')
            ->whereNull('parent_id')
            ->doesntHave('categories')
            ->get();

        $categorySpecifications = Specification::with(['children','product_values'])
            ->orderByDesc('id')
            ->whereNull('parent_id')
            ->whereHas('categories', function ($query) use ($product) {
                $query->whereIn('product_category_id', $product->categories->pluck('id'));
            })->get();

        $specifications = $generalSpecifications->merge($categorySpecifications);

        $sortedSpecifications = $specifications->sortBy(function ($specification) {
            return $specification->type == 'select' ? 0 : 1;
        });

        $sortedSpecifications = $sortedSpecifications->values()->all();

        return view('admin.product.product.variant.create',
            compact('product', 'sortedSpecifications'));

    }

    public function store(VariantRequest $request)
    {
        $this->variantService->create(VariantDTO::fromRequest($request));
        if (VariantDTO::fromRequest($request)->getHasError() === true) {
            return redirect()->back()->with('info', 'مقدار قیمت با تخفیف نرخ نباید از مقدار قیمت بالاتر باشد یا یکی از موارد مقدار ندارد(مقادیر معتبر اضافه شدند)');
        } elseif(VariantDTO::fromRequest($request)->getHasErrorSpecification() === true){
            return redirect()->back()->with('info', 'مقدار مشخصه را اضافه کنید');
        }
        else {
            return redirect()->back()->with('success', 'آیتم های جدید اضافه شد.');
        }

    }

    public function destroy($id)
    {
        $this->variantService->delete($id);
        return response()->json(['success' => 'آیتم مورد نظر با موفقیت حذف شد.'], 200);

    }

    public function variants(Request $request)
    {
        $variants = ProductVariant::orderByDesc('id')->with('specifications')->whereProductId($request->get('product_id'))->get();
        return response()->json(["variants" => $variants]);
    }


}
