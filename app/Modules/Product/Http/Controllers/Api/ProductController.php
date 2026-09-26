<?php

namespace App\Modules\Product\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Product\DTO\Api\TimerDTO;
use App\Modules\Product\DTO\ImageDTO;
use App\Modules\Product\Http\Requests\Api\ProductRequest;
use App\Modules\Product\DTO\Api\ProductDTO;
use App\Modules\Product\Http\Requests\Api\TimerRequest;
use App\Modules\Product\Http\Requests\ImageRequest;
use App\Modules\Product\Services\Api\ProductService;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\Specification;
use App\Modules\Product\Services\ImageService;

class ProductController extends Controller
{
    public function __construct(protected ProductService $service, protected ImageService $imageService)
    {
        $this->middleware('auth:admin_jwt');
    }
    public function index(Request $request)
    {
        return response()->json([
            'success' => true,
            'page' => $request->get("page") ?? 1,
            'data' => $this->service->list($request),
        ]);
    }
    public function show($id)
    {
        return response()->json([
            'success' => true,
            'data' => $this->service->find($id),
        ]);
    }
    public function store(ProductRequest $request)
    {
        $dto = ProductDTO::fromRequest($request);
        $product = $this->service->store($dto);

        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    }
    public function update(ProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $dto = ProductDTO::fromRequest($request);
        $updated = $this->service->update($product, $dto);

        return response()->json([
            'success' => true,
            'data' => $updated,
        ]);
    }
    public function timer(TimerRequest $request,$id)
    {
        $product = Product::findOrFail($id);
        if ($product->discounted_price != null) {
            $data = $this->service->timer($product,TimerDTO::fromRequest($request));
        }else{
            $data = [
                'success' => false,
                'data' => ['error' => 'این محصول تخیفی ندارد که بخواهیید برای ان تایمر بگذارید']
            ];
        }
        return response()->json($data);
    }
    public function specificationSelect(Request $request,$id){
        $product = Product::findOrFail($id);
        $values = array_map(fn($v) => is_array($v) ? $v[0] : $v, $request->all());
        $data = $this->service->specificationSelect($product,$values);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
    public function specificationText(Request $request,$id){
        $product = Product::findOrFail($id);
        $values = $request->all();
        $data = $this->service->specificationText($product,$values);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
