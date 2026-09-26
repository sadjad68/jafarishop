<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Order\Http\Resources\BasketCollection;
use App\Modules\Order\Services\BasketService;
use App\Modules\Product\Entities\Product;


class BasketController extends Controller
{
    public function itemCount()
    {
        $basket = BasketService::findBasketAndCheckStock();
        $basketCount = $basket ? @$basket->items()->sum('quantity') : 0;
        return response()->json([
            'basketCount' => @$basketCount,
        ], 200);
    }
    public function add(Request $request)
    {
        $product_id = @$request->get('product_id');
        $product_variant_id = @$request->get('product_variant_id');
        $quantity = @$request->get('quantity');
        $cart = @$request->get('cart');

      return BasketService::create($product_id,$product_variant_id,$quantity,$cart);

    }
    //cart
    public function cart()
    {
        $basket = BasketService::findBasketAndCheckStock();
if ($basket && $basket->discounted_id != null){
    $basket->update([
       'discounted_id' => null
    ]);
}
        return view('pages.cart.checkout', compact('basket'));
    }
    public function cartItems()
    {
        $basket = BasketService::findBasketAndCheckStock();
        $basketCollection = $basket ? new BasketCollection(@$basket->items) : [];


        return response()->json([
            'basketCollection' => @$basketCollection,
        ], 200);

    }
    //delete
    public function delete(){
         BasketService::delete();
         return redirect()->back()->with('success','سبد خرید با موفقیت حذف شد');
    }
    public function removeItem(Request $request){
         BasketService::removeItem($request->get('item_id'));
         return redirect()->back()->with('success','محصول موردنظر با موفقیت حذف شد');
    }
    //price
    public function listPrice(){
        $price = BasketService::listPrice();
        return response()->json($price);
    }

}
