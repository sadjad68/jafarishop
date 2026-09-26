<?php

namespace App\Modules\Contact\Entities;


use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class Contact extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'mobile', 'title', 'message', 'status',
    ];


    public function getDateAttribute()
    {
        return jdate('d F Y H:i', $this->created_at->timestamp);

    }
}
