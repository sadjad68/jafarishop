<?php

namespace App\Modules\Order\Library;

class PaymentVerifyDTO
{
    protected string $status;
    protected array $verify_data;

    public function getStatus()
    {
        return $this->status;
    }

    public function getVerifyData()
    {
        return $this->verify_data;
    }

    public static function fromData($status, $verify_data = [])
    {
        $self = new self();
        $self->status = $status;
        $self->verify_data = $verify_data;
        return $self;
    }
}
