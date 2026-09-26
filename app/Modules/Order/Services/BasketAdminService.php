<?php

namespace App\Modules\Order\Services;

use App\Modules\General\Helper\DateHelper;
use App\Modules\Order\Entities\Basket;
use App\Modules\Location\Entities\City;
use App\Modules\Product\Entities\Product;
use App\Modules\User\Entities\User;
use Illuminate\Http\Request;


class BasketAdminService
{
    public function getList($request){

        $query = Basket::query()->with(['user', 'items']);

            // فیلتر کاربر
            if ($request->boolean('has_user')) {
                $query->whereNot("user_id",null);
                if (@$request->user_ids && $userIds = mb_split(',',(@$request->user_ids ?? ''))) {
                    $query->whereIn('user_id', $userIds);
                }
            }

            if ($request->has('mobile')) {
                $query->whereHas('user', function ($q) use ($request) {
                    $q->where('mobile', 'like', "%{$request->mobile}%");
                });
            }

            // فیلتر محصول
            if (@$request->product_ids && $productIds = mb_split(',',(@$request->product_ids ?? ''))) {
                $query->whereHas('items', function ($q) use ($productIds) {
                    $q->whereIn('product_id', $productIds);
                });
            }

            // اگر تیک "دارای آدرس" زده شده
            if ($request->boolean('has_address')) {
                $query->whereNot("address_id",null);
                $query->whereHas('address', function ($q) use ($request) {
                    if ($request->filled('state_id')) $q->where('state_id', $request->state_id);
                    if ($request->filled('city_id')) $q->where('city_id', $request->city_id);
                    if ($request->filled('postal_code')) $q->where('postal_code', 'like', "%{$request->postal_code}%");
                });
            }

        if ($request->filled('from_date') || $request->filled('to_date')) {
            $from_date = $request->filled('from_date') ? DateHelper::convertDate($request->from_date) : null;
            $to_date = $request->filled('to_date') ? DateHelper::convertDate($request->to_date) : null;

            if (!empty($from_date) && !empty($to_date)) {
                $query->whereBetween('created_at', [
                    $from_date . ' 00:00:00',
                    $to_date . ' 23:59:59',
                ]);
            } elseif (!empty($from_date)) {
                $query->where('created_at', '>=', $from_date . ' 00:00:00');
            } elseif (!empty($to_date)) {
                $query->where('created_at', '<=', $to_date . ' 23:59:59');
            }
        }

        return $query->orderBy("updated_at","desc")->paginate(20);
    }
    public function getDetailData(Basket $basket){

        $shipping_price = BasketService::calculateShipping($basket);
        return compact('shipping_price');
    }
    public function getUsers(Request $request)
    {
        $search = trim($request->get('search'));
        $ids = $request->get('ids');

        $query = User::query()->select('id', 'full_name', 'mobile');

        // اگر id مشخص شده
        if ($ids) {
            $ids = is_array($ids) ? $ids : explode(',', $ids);
            $query->whereIn('id', $ids);
        }

        // اگر search داده شده
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        $users = $query->orderByDesc('id')->limit(20)->get();
        return response()->json($users);
    }

    public function getProducts(Request $request)
    {
        $search = trim($request->get('search'));
        $ids = $request->get('ids');

        $query = Product::query()->select('id', 'title', 'price');

        if ($ids) {
            $ids = is_array($ids) ? $ids : explode(',', $ids);
            $query->whereIn('id', $ids);
        }

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        $products = $query->orderByDesc('id')->limit(20)->get();
        return response()->json($products);
    }


    public function getCities(Request $request)
    {
        $stateId = $request->get('state_id');

        $cities = City::query()
            ->select('id', 'name', 'state_id')
            ->when($stateId, fn($q) => $q->where('state_id', $stateId))
            ->orderBy('name')
            ->get();

        return response()->json($cities);
    }


}
