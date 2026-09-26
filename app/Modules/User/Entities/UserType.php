<?php

namespace App\Modules\User\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'type'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
