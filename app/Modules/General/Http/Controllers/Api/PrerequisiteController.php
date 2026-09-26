<?php

namespace App\Modules\General\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\General\Services\Api\PrerequisiteService;

class PrerequisiteController extends Controller
{
    public function __construct(protected PrerequisiteService $service)
    {
        $this->middleware('auth:admin_jwt');
    }
    public function brands(Request $request)
    {
        return response()->json([
            'success' => true,
            'page' => $request->get("page") ?? 1,
            'data' => $this->service->brands($request),
        ]);
    }
    public function tags(Request $request)
    {
        return response()->json([
            'success' => true,
            'page' => $request->get("page") ?? 1,
            'data' => $this->service->tags($request),
        ]);
    }
    public function services(Request $request)
    {
        return response()->json([
            'success' => true,
            'page' => $request->get("page") ?? 1,
            'data' => $this->service->services($request),
        ]);
    }
}
