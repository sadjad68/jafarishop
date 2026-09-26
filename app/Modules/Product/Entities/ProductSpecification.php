<?php

namespace App\Modules\Product\Entities;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class ProductSpecification extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'product_specification';
    protected $fillable = [
        'value', 'specification_id','product_id','parent_id'
    ];


    public function getDateAttribute()
    {
        return jdate('d F Y', $this->updated_at->timestamp);
    }
    public function products()
    {
        return $this->belongsTo(Product::class);
    }
    public function specification()
    {
        return $this->belongsTo(Specification::class);
    }
    public function parent()
    {
        return $this->hasOne(ProductSpecification::class, 'id', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ProductSpecification::class, 'parent_id')->with('children');
    }

}
