<?php

namespace App\Modules\Product\Http\Controllers;


use App\Modules\Faq\Entities\Faq;
use App\Modules\Tag\Entities\Tag;
use App\Modules\Product\DTO\SpfDTO;
use App\Modules\Product\DTO\VideoFaqDTO;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\Property;
use App\Modules\Product\Entities\Video;
use App\Modules\Product\Http\Requests\SpfRequest;
use App\Modules\Product\Http\Requests\VideoFaqRequest;
use App\Modules\Product\Services\SpfService;
use App\Modules\Product\Services\VideoFaqService;
use App\Modules\Product\Entities\Specification;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;

class ProductDetailController extends Controller
{
    protected $videoFaqService;
    protected $spfService;
    public function __construct(
        VideoFaqService $videoFaqService,
        SpfService $spfService,
    )
    {
        $this->videoFaqService = $videoFaqService;
        $this->spfService = $spfService;

    }
    /**
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.product.product.video-faq.create', compact('product'));

    }
    public function videos(Request $request)
    {
        $videos = Video::orderByDesc('id')->whereProductId($request->get('product_id'))->get();
        $faqs = Faq::orderByDesc('id')->where('faqable_id',$request->get('product_id'))
            ->where('faqable_type','App\Modules\Product\Entities\Product')->get();
        return response()->json(["videos" => $videos,"faqs"=>$faqs]);
    }


    public function store(VideoFaqRequest $request)
    {
        if ($request->get('faqs') == null && $request->get('videos') == null){
            return redirect()->back()
                ->with('error', 'حداقل یک آیتم را پر کنید');
        }
        $this->videoFaqService->create(VideoFaqDTO::fromRequest($request));
        return redirect()->back()
            ->with('success', 'آیتم های جدید اضافه شد.');
    }


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroyVideo($id)
    {
        $this->videoFaqService->deleteVideo($id);
        return response()->json(['success' => 'آیتم مورد نظر با موفقیت حذف شد.'], 200);

    }
    public function destroyFaq($id)
    {
        $this->videoFaqService->deleteFaq($id);
        return response()->json(['success' => 'آیتم مورد نظر با موفقیت حذف شد.'], 200);

    }
    //
    public function indexProp($id)
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
            })
            ->get();

        $specifications = $generalSpecifications->merge($categorySpecifications);

        $sortedSpecifications = $specifications->sortBy(function ($specification) {
            return $specification->type == 'select' ? 0 : 1;
        });

        $sortedSpecifications = $sortedSpecifications->values()->all();

        $tags = Tag::orderByDesc('id')->get();
        $properties = Property::orderByDesc('id')->whereProductId($id)->get();

        return view('admin.product.product.properties-spfs-tags.create',
            compact('product','tags','sortedSpecifications','properties'));

    }
    public function props(Request $request)
    {
        $properties = Property::orderByDesc('id')->whereProductId($request->get('product_id'))->get();
        $product = Product::with(['specifications'])->findOrFail($request->get('product_id'));

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
            })
            ->get();

        $specifications = $generalSpecifications->merge($categorySpecifications);

        $sortedSpecifications = $specifications->sortBy(function ($specification) {
            return $specification->type == 'select' ? 0 : 1;
        });

        $sortedSpecifications = $sortedSpecifications->values()->all();

        return response()->json(["properties" => $properties,"specifications"=>$sortedSpecifications]);
    }
    public function productSpecification(Request $request)
    {
        $product = Product::findOrFail($request->get('product_id'));
        $specification_ids =$product->specifications->pluck('id');
        return response()->json(["specification_ids" => $specification_ids]);

    }
    public function destroyProp($id)
    {
        $this->spfService->deleteProperty($id);
        return response()->json(['success' => 'آیتم مورد نظر با موفقیت حذف شد.'], 200);

    }
    public function destroyProductSpecification($id)
    {
        $this->spfService->deleteSpecification($id);
        return response()->json(['success' => 'آیتم مورد نظر با موفقیت حذف شد.'], 200);

    }

    public function storeProp(SpfRequest $request)
    {

        $this->spfService->create(SpfDTO::fromRequest($request));
        return redirect()->back()
            ->with('success', 'آیتم های جدید اضافه شد.');
    }

}
