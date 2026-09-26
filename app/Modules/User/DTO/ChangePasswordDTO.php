<?php

namespace App\Modules\User\DTO;

use App\Modules\General\Helper\NumberHelper;
use App\Modules\User\Http\Requests\AdminRequest;
use App\Modules\User\Http\Requests\ChangePasswordRequest;
use Illuminate\Http\UploadedFile;

class ChangePasswordDTO
{
    protected string $password;

    public function getPassword(): string
    {
        return bcrypt($this->password);
    }
    public static function fromRequest(ChangePasswordRequest $request)
    {
        $self = new self();
        $self->password = $request->get('password');
        return $self;
    }
}
