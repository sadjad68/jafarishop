<?php

namespace App\Modules\Product\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Product\DTO\Api\ProductCategoryDTO;
use App\Modules\Product\Http\Requests\Api\ProductCategoryRequest;
use App\Modules\Product\Services\Api\ProductCategoryService;

class ProductCategoryController extends Controller
{
    protected $service;

    public function __construct(ProductCategoryService $service)
    {
        $this->service = $service;
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

    public function store(ProductCategoryRequest $request)
    {
        return response()->json([
            'success' => true,
            'data' => $this->service->create(ProductCategoryDTO::fromRequest($request)),
        ]);
    }

    public function update(ProductCategoryRequest $request, $id)
    {
        return response()->json([
            'success' => true,
            'data' => $this->service->update($id, ProductCategoryDTO::fromRequest($request)),
        ]);
    }
}
