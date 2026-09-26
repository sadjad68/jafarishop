<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Modules\General\Helper\NumberHelper;
use App\Modules\Location\Entities\Address;
use App\Modules\Location\Entities\City;
use App\Modules\Location\Entities\State;
use App\Modules\Location\Http\Resources\AddressCollection;
use App\Modules\Location\Services\AddressService;
use App\Modules\Location\Services\CityService;
use App\Modules\Location\Services\StateService;
use App\Modules\Order\Http\Resources\BasketCollection;
use App\Modules\Order\Services\BasketService;
use App\Modules\Product\Entities\Product;


class AddressController extends Controller
{
    //list
    public function list()
    {
        $basket = BasketService::findBasketAndCheckStock();
        if (!$basket){
            return redirect(
                route('basket.cart')
            );
        }
        //logea
        return view(
            'pages.cart.address', compact('basket'));

    }
    public function userAddresses()
    {
        $addresses = AddressService::find();
        $addressCollection = new AddressCollection($addresses['addresses']);
        return response()->json([
            'addresses' => @$addressCollection,
        ], 200);
    }
    public function states()
    {
        $states = StateService::findAll();
        return response()->json(['states' => $states]);
    }
    public function city(Request $request)
    {
        $req = $request->all();
        $cities = null;
        $cities = CityService::findOne($req['state_id']);

        return response()->json(['cities' => $cities]);
    }
    public function create(Request $request)
    {
        $user_id = @Auth::id();
        $state_id = @$request->get('state_id');
        $city_id = @$request->get('city_id');
        $address = @$request->get('address');
        $postal_code = NumberHelper::persian2LatinDigit(@$request->get('postal_code'));
        $receiptor_full_name = @$request->get('receiptor_full_name');
        $receiptor_mobile = NumberHelper::persian2LatinDigit(@$request->get('receiptor_mobile'));
       AddressService::create($user_id, $state_id, $city_id, $address, $postal_code, $receiptor_full_name, $receiptor_mobile);
        return redirect()->back()->with('success', 'آدرس با موفقیت اضافه شد');

    }
    public function edit(Request  $request)
    {
        $address = AddressService::findOne($request->get('address_id'));
        return response()->json(['address' => $address]);

    }
    public function update(Request $request)
    {
        $user_id = @Auth::id();
        $address_id = @$request->get('address_id');
        $state_id = @$request->get('state_id');
        $city_id = @$request->get('city_id');
        $address = @$request->get('address');
        $postal_code = NumberHelper::persian2LatinDigit(@$request->get('postal_code'));
        $receiptor_full_name = @$request->get('receiptor_full_name');
        $receiptor_mobile = NumberHelper::persian2LatinDigit(@$request->get('receiptor_mobile'));
        AddressService::update($address_id,$user_id, $state_id, $city_id, $address, $postal_code, $receiptor_full_name, $receiptor_mobile);
        return redirect()->back()->with('success', 'آدرس با موفقیت ویرایش شد');

    }
    public function shipments(Request $request)
    {
        $address = AddressService::findOne($request->get('address_id'));
        $basket = BasketService::findBasketAndCheckStock();
        if (! $basket) {
            return response()->json(['error' => 'سبد خرید یافت نشد.', 'shipping_methods' => []], 404);
        }
        $basket->update(['address_id' => @$request->get('address_id')]);
        $shipping_methods = BasketService::checkShipmentPriceBaseOnAddress($address);

        return response()->json(['shipping_methods' => @$shipping_methods]);
    }
    public function setShipments(Request $request)
    {
        try {
            $basket = BasketService::findBasketAndCheckStock();
            if (! $basket) {
                return response()->json(['success' => false, 'message' => 'سبد خرید یافت نشد.'], 404);
            }
            $shipping_method_id = $request->get('shipping_method_id');
            if ($shipping_method_id === null || $shipping_method_id === '') {
                return response()->json(['success' => false, 'message' => 'روش ارسال انتخاب نشده است.'], 422);
            }
            $basket->update([
                'shipping_method_id' => $shipping_method_id,
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $err) {
            return response()->json(['success' => false, 'message' => $err->getMessage()], 500);
        }
    }
    //price
    public function addressPrice()
    {
        try {
            $price = BasketService::addressPrice();

            return response()->json($price);
        } catch (\Exception $err) {
            $basket = BasketService::findBasketAndCheckStock();
            if ($basket) {
                $basket->update([
                    'shipping_method_id' => null,
                ]);
            }

            return response()->json(['error' => $err->getMessage()], 500);
        }
    }


}
