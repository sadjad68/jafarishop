<?php

namespace App\Modules\Blog\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Blog\DTO\Api\BlogCategoryDTO;
use App\Modules\Blog\Http\Requests\Api\BlogCategoryRequest;
use App\Modules\Blog\Services\Api\BlogCategoryService;

class BlogCategoryController extends Controller
{
    protected $service;

    public function __construct(BlogCategoryService $service)
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

    public function store(BlogCategoryRequest $request)
    {
        return response()->json([
            'success' => true,
            'data' => $this->service->create(BlogCategoryDTO::fromRequest($request)),
        ]);
    }

    public function update(BlogCategoryRequest $request, $id)
    {
        return response()->json([
            'success' => true,
            'data' => $this->service->update($id, BlogCategoryDTO::fromRequest($request)),
        ]);
    }
}
