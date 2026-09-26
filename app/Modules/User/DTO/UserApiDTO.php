<?php

namespace App\Modules\User\DTO;

use Illuminate\Http\Request;
use App\Modules\General\Helper\NumberHelper;

class UserApiDTO
{

    protected string $full_name;

    public function getFullName(): string
    {
        return $this->full_name;
    }

    protected string $email;

    public function getEmail(): string
    {
        return $this->email;
    }

    protected string $mobile;

    public function getMobile(): string
    {
        return $this->mobile;
    }

    public static function fromRequest(Request $request)
    {
        $self = new self();
        $self->full_name = $request->get('full_name');
        $self->email = $request->get('email');
        $self->mobile = NumberHelper::persian2LatinDigit($request->get('mobile'));
        return $self;
    }
}
