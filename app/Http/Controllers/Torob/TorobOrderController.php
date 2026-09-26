<?php

namespace App\Http\Controllers\Torob;

use App\Http\Controllers\Controller;
use App\Services\Torob\TorobOrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TorobOrderController extends Controller
{
    public function orders(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'purchase_timestamp_gt' => ['nullable', 'date'],
            'limit' => ['nullable', 'integer', 'min:1'],
        ]);

        $limit = isset($validated['limit'])
            ? (int) $validated['limit']
            : (int) config('torob.per_page', 100);

        return response()->json(
            [
                'success' => true,
                'data' => TorobOrderService::listOrders(
                    @$validated['purchase_timestamp_gt'],
                    $limit
                ),
            ],
            200,
            ['Content-Type' => 'application/json; charset=UTF-8'],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }
}
