<?php

namespace App\Modules\Product\Http\Controllers\Api;


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
    public function __construct(protected ImageService $imageService,)
    {
        $this->middleware('auth:admin_jwt');
    }
    public function create(ImageRequest $request, $id)
    {
        if($id == null){
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ]);
        }
        $request['product_id'] = $id;
        $dto = ImageDTO::fromRequest($request);
        $this->imageService->create($dto);
        $images = $this->imageService->getProductImages($id);
        return response()->json([
            'success' => true,
            'images' => $images
        ]);
    }
    public function addVariants(ImageRequest $request, $id)
    {
        $image = $this->imageService->findOne($id);
        if($image == null){
            return response()->json([
                'success' => false,
                'message' => 'Image not found'
            ]);
        }
        $variants = json_decode($request->variants, true);
        $request['product_variant_id'] = $variants;
        $request['product_id'] = $image->product_id;
        $dto = ImageDTO::fromRequest($request);
        $this->imageService->create($dto);
        $images = $this->imageService->getProductImages($image->product_id);
        return response()->json([
            'success' => true,
            'images' => $images
        ]);
    }

    public function setImageThumbnail($id)
    {
        $image = $this->imageService->findOne($id);
        $product = Product::findOrFail($image->product_id);
        foreach ($product->images as $img) {
            $img->update([
                'thumbnail' => 0
            ]);
        }
        $image->update([
            'thumbnail' => 1
        ]);
        $images = $this->imageService->getProductImages($image->product_id);
        return response()->json([
            'success' => true,
            'image' => $images
        ]);
    }
}
