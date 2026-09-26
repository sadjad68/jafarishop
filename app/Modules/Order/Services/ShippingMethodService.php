<?php
namespace App\Modules\Order\Services;

use App\Modules\General\Helper\FileManager;
use App\Modules\Order\DTO\OrderShippingStatusDTO;
use App\Modules\Order\DTO\ShippingMethodDTO;
use App\Modules\Order\Entities\OrderShippingStatus;
use App\Modules\Order\Entities\ShippingMethod;

class ShippingMethodService
{
    public function create(ShippingMethodDTO $shippingMethodDTO)
    {
        ini_set('max_input_vars', 5000);
        ini_set('max_multipart_body_parts', 5000);
        $method = ShippingMethod::create([
            'title' => $shippingMethodDTO->getTitle(),
            'price' => $shippingMethodDTO->getPrice(),
            'price_ceiling' => $shippingMethodDTO->getPriceCeiling(),
            'status' => $shippingMethodDTO->getStatus(),
            'freight_balance' => $shippingMethodDTO->getFreightBalances(),
            'chapar_type' => $shippingMethodDTO->getChaparType(),
            'type' => $shippingMethodDTO->getType(),
            'description' => $shippingMethodDTO->getDescription(),
            'sender_name' => $shippingMethodDTO->getSenderName(),
            'sender_company' => $shippingMethodDTO->getSenderCompany(),
            'sender_phone' => $shippingMethodDTO->getSenderPhone(),
            'sender_mobile' => $shippingMethodDTO->getSenderMobile(),
            'sender_email' => $shippingMethodDTO->getSenderEmail(),
            'sender_city_id' => $shippingMethodDTO->getSenderCityId(),
            'sender_address' => $shippingMethodDTO->getSenderAddress(),
            'sender_postal_code' => $shippingMethodDTO->getSenderPostalCode(),
            'config' => json_encode([
                'password' => $shippingMethodDTO->getPassword(),
                'user_name' => $shippingMethodDTO->getUserName(),
            ], JSON_UNESCAPED_UNICODE)

        ]);
        $method->cities()->attach($shippingMethodDTO->getCities());
    }
    public function update(int $id, ShippingMethodDTO $shippingMethodDTO)
    {
        $method = ShippingMethod::findOrfail($id);

        ini_set('max_input_vars', 5000);
        ini_set('max_multipart_body_parts', 5000);
        $method->update([
            'title' => $shippingMethodDTO->getTitle(),
            'price' => $shippingMethodDTO->getPrice(),
            'price_ceiling' => $shippingMethodDTO->getPriceCeiling(),
            'status' => $shippingMethodDTO->getStatus(),
            'freight_balance' => $shippingMethodDTO->getFreightBalances(),
            'chapar_type' => $shippingMethodDTO->getChaparType(),
            'type' => $shippingMethodDTO->getType(),
            'description' => $shippingMethodDTO->getDescription(),
            'sender_name' => $shippingMethodDTO->getSenderName(),
            'sender_company' => $shippingMethodDTO->getSenderCompany(),
            'sender_phone' => $shippingMethodDTO->getSenderPhone(),
            'sender_mobile' => $shippingMethodDTO->getSenderMobile(),
            'sender_email' => $shippingMethodDTO->getSenderEmail(),
            'sender_city_id' => $shippingMethodDTO->getSenderCityId(),
            'sender_address' => $shippingMethodDTO->getSenderAddress(),
            'sender_postal_code' => $shippingMethodDTO->getSenderPostalCode(),
            'config' => json_encode([
                'password' => $shippingMethodDTO->getPassword(),
                'user_name' => $shippingMethodDTO->getUserName(),
            ], JSON_UNESCAPED_UNICODE)
        ]);
        $method->cities()->sync($shippingMethodDTO->getCities());
    }

    public function deleteOne(int $id): void
    {
        $status = ShippingMethod::findOrFail($id);

        $status->delete();
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



}
