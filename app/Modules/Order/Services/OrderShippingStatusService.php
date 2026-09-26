<?php
namespace App\Modules\Order\Services;

use App\Modules\General\Helper\FileManager;
use App\Modules\Order\DTO\OrderShippingStatusDTO;
use App\Modules\Order\Entities\OrderShippingStatus;

class OrderShippingStatusService
{
    public function create(OrderShippingStatusDTO $orderShippingStatusDTO)
    {
        if ($orderShippingStatusDTO->getDefault() == 1){
            OrderShippingStatus::orderBy('id','DESC')->update([
               'default' => 0
            ]);
        }
        if ($orderShippingStatusDTO->getForSend() == 1){
            OrderShippingStatus::orderBy('id','DESC')->update([
                'for_send' => 0
            ]);
        }
        $status = OrderShippingStatus::create([
            'title' => $orderShippingStatusDTO->getTitle(),
            'color' => $orderShippingStatusDTO->getColor(),
            'default' => $orderShippingStatusDTO->getDefault(),
            'for_send' => $orderShippingStatusDTO->getForSend(),
            'sending_sms' => $orderShippingStatusDTO->getSendingSms(),
        ]);

    }
    public function update(int $id, OrderShippingStatusDTO $orderShippingStatusDTO)
    {
        $status = OrderShippingStatus::findOrfail($id);
        if ($orderShippingStatusDTO->getDefault() == 1){
            OrderShippingStatus::orderBy('id','DESC')->where('id','<>',$id)->update([
                'default' => 0
            ]);
        }
        if ($orderShippingStatusDTO->getForSend() == 1){
            OrderShippingStatus::orderBy('id','DESC')->update([
                'for_send' => 0
            ]);
        }
        $status->update([
            'title' => $orderShippingStatusDTO->getTitle(),
            'color' => $orderShippingStatusDTO->getColor(),
            'default' => $orderShippingStatusDTO->getDefault(),
            'for_send' => $orderShippingStatusDTO->getForSend(),
            'sending_sms' => $orderShippingStatusDTO->getSendingSms(),
        ]);

    }

    public function deleteOne(int $id): void
    {
        $status = OrderShippingStatus::findOrFail($id);
        if ($status->default == 1){
            throw new \InvalidArgumentException('امکان حذف وضعیت پیش فرض وجود ندارد');
        }
        else{
            $status->delete();
        }

    }

    //
    public static function findAll($query = [], $except_id = null, $limit = null)
    {
        $statuses = OrderShippingStatus::query();
        if ($except_id) {
            $statuses->where('id', '<>', $except_id);
        }

        if (isset($query['title'])) {
            $statuses->where('title','LIKE','%'.$query['title'].'%');
        }
        if ($limit != null) {
            return $statuses->orderby('id', 'DESC')->take($limit)->get();
        }else{
            return $statuses->orderby('id', 'DESC')->get();
        }
    }
    public static function findById($id)
    {

        return OrderShippingStatus::findOrFail($id);
    }



}
