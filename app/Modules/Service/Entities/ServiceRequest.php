<?php
namespace App\Modules\Service\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\General\Helper\FileManager;

class ServiceRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'service_id',
        'full_name',
        'phone',
        'description',
        'images',
        'is_read',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function service(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function getCreatedAtDate(): array|string
    {
        return jdate('d F Y', $this->created_at->timestamp);
    }

// در مدل ServiceRequest.php
    public function getFullImagePathsAttribute(): array
    {
        if (!$this->images) return [];

        return array_map(function ($image) {
            return \App\Modules\General\Helper\FileManager::serveFile(
                'uploads/service-requests/big/' . $image,
                'assets/notfounds/services-img.jpg'
            );
        }, $this->images);
    }

}
