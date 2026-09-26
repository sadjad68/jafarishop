<?php

namespace App\Modules\Faq\Entities;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\User\Entities\User;
use function App\Modules\Comment\Entities\jdate;

class Faq extends Model
{
    use SoftDeletes;


    protected $table = "faqs";
    protected $fillable = [
        'question',
        'answer',
        'faqable_id',
        'faqable_type',
        'active',

    ];
    public function getFaqableTypeAttribute($value)
    {
        return \App\Services\CmsCoreNamespaceConverter::normalizeClassName($value);
    }

    public function setFaqableTypeAttribute($value)
    {
        $this->attributes['faqable_type'] = \App\Services\CmsCoreNamespaceConverter::normalizeClassName($value);
    }

    public function faqable()
    {
        return $this->morphTo();
    }


}
