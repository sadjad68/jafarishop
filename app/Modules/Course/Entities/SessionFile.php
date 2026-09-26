<?php

namespace App\Modules\Course\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionFile extends Model
{
    protected $fillable = [
        'session_id',
        'file',
    ];

    public function session()
    {
        return $this->belongsTo(Session::class);
    }
}
