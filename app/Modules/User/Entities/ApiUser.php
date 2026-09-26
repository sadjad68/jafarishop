<?php

namespace App\Modules\User\Entities;

use App\Modules\User\Entities\User as BaseUser;
use Tymon\JWTAuth\Contracts\JWTSubject;

class ApiUser extends BaseUser implements JWTSubject
{
    public $table = 'users';
    public function getJWTIdentifier() { return $this->getKey(); }
    public function getJWTCustomClaims() { return []; }
}
