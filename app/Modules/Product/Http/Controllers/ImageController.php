<?php

namespace App\Modules\Product\Http\Controllers;


use App\Modules\Faq\Entities\Faq;
use App\Modules\Product\DTO\ImageMainVariantDTO;
use App\Modules\Product\Http\Requests\MainVariantRequest;
use App\Modules\Tag\Entities\Tag;
use App\Modules\Product\DTO\ImageDTO;
use App\Modules\Product\DTO\SpfDTO;
use App\Modules\Product\DTO\VideoFaqDTO;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\Property;
use App\Modules\Product\Entities\Video;
use App\Modules\Product\Http\Requests\ImageRequest;
use App\Modules\Product\Http\Requests\SpfRequest;
use App\Modules\Product\Http\Requests\VideoFaqRequest;
use App\Modules\Product\Services\ImageService;
use App\Modules\Product\Services\SpfService;
use App\Modules\Product\Services\VideoFaqService;
use App\Modules\Product\Entities\Specification;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;

class ImageController extends Controller
{
    protected $imageService;
    public function __construct(
        ImageService $imageService,
    )
    {
        $this->imageService = $imageService;
    }

    /**
     * /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($id)
    {
        $data = Product::findOrFail($id);

        return view('admin.product.product.image.create', compact('data',));

    }


    public function store(ImageRequest $request)
    {
        $this->imageService->create(ImageDTO::fromRequest($request));
        return redirect()->back()
            ->with('success', 'آیتم های جدید اضافه شد.');
    }
}
