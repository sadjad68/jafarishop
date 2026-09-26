<?php

namespace App\Modules\Order\Library;

class PaymentCancelDTO
{
    protected string $successful;
    protected array $errorData;

    public function getSuccessful()
    {
        return $this->successful;
    }

    public function getErrorData()
    {
        return $this->errorData;
    }

    public static function fromData($successful, $errorData = [])
    {
        $self = new self();
        $self->successful = $successful;
        $self->errorData = $errorData;
        return $self;
    }
}
