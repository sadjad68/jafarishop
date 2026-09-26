<?php

namespace App\Modules\Order\Library;

class PaymentPostDTO
{
    protected string $status;
    protected array $post_data;

    public function getStatus()
    {
        return $this->status;
    }

    public function getPostData()
    {
        return $this->post_data;
    }

    public static function fromData($status, $post_data = [])
    {
        $self = new self();
        $self->status = $status;
        $self->post_data = $post_data;
        return $self;
    }
}
