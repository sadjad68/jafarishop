<?php

namespace App\Modules\Order\Observers;


use App\Modules\Order\Entities\Basket;
use App\Modules\Order\Entities\Order;
use App\Modules\Order\Entities\OrderHistory;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     *
     * @param  \App\Modules\Order\Entities\Order $Order
     * @return void
     */
    public function created(Order $Order)
    {
        \Log::info('hi Order created');
        if ($Order->isDirty('order_status') || $Order->isDirty('shipping_status_id')) {
            OrderHistory::create([
                'order_id' => $Order->id,
                'order_status' => $Order['order_status'],
                'shipping_status_id' => $Order['shipping_status_id'],
            ]);
        }
    }

    /**
     * Handle the Order "updated" event.
     *
     * @param  \App\Modules\Order\Entities\Order $Order
     * @return void
     */
    public function updated(Order $Order)
    {
        \Log::info('hi Order updated');
        if ($Order->isDirty('order_status') || $Order->isDirty('shipping_status_id')) {
            OrderHistory::create([
                'order_id' => $Order->id,
                'order_status' => $Order['order_status'],
                'shipping_status_id' => $Order['shipping_status_id'],
            ]);
            if ($Order['order_status'] == "paid"){
                $basket = Basket::find($Order['basket_id']);
                if ($basket){
                    $basket->delete();
                }
            }
        }
    }

    /**
     * Handle the Order "deleted" event.
     *
     * @param  \App\Modules\Order\Entities\Order $Order
     * @return void
     */
    public function deleted(Order $Order)
    {
        //
    }

    /**
     * Handle the Order "forceDeleted" event.
     *
     * @param  \App\Modules\Order\Entities\Order $Order
     * @return void
     */
    public function forceDeleted(Order $Order)
    {
        //
    }
}
