<?php

namespace App\Modules\Product\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Product\Services\Api\SpecificationService;

class SpecificationController extends Controller
{
    public function __construct(protected SpecificationService $service)
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
    public function values($id)
    {
        return response()->json([
            'success' => true,
            'data' => $this->service->values($id),
        ]);
    }

    public function products($id, Request $request)
    {
        return $this->service->productShow($id, $request);
    }
}
