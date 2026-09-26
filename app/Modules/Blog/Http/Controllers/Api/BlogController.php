<?php

namespace App\Modules\Blog\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Blog\DTO\BlogDTO;
use App\Modules\Blog\Http\Requests\BlogRequest;
use App\Modules\Blog\Services\Api\BlogService;

class BlogController extends Controller
{
    protected $service;

    public function __construct(BlogService $service)
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

    public function store(BlogRequest $request)
    {
        return response()->json([
            'success' => true,
            'data' => $this->service->create(BlogDTO::fromRequest($request)),
        ]);
    }

    public function update(BlogRequest $request, $id)
    {
        return response()->json([
            'success' => true,
            'data' => $this->service->update($id, BlogDTO::fromRequest($request)),
        ]);
    }
}
