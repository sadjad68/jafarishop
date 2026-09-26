<?php

namespace App\Modules\Order\Library;

use App\Modules\Order\Entities\Order;

interface PaymentGateway
{
    public function getBankToken($amount, Order $order): PaymentPostDTO;

    public function verifyTransaction(array $callbackData, int $price, Order $order): PaymentVerifyDTO;

    public function redirect(PaymentPostDTO $postData, Order $order);
}
