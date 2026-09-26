<?php

namespace App\Modules\Service\Services;

use App\Modules\General\Helper\FileUploader;
use App\Modules\Service\DTO\ServiceRequestDTO;
use App\Modules\Service\Entities\ServiceRequest;

class ServiceServiceRequest
{
    protected ServiceRequest $serviceRequestEntity;

    public function __construct(ServiceRequest $serviceRequestEntity)
    {
        $this->serviceRequestEntity = $serviceRequestEntity;
    }

    public function findAll($limit = 10)
    {
        return $this->serviceRequestEntity->orderBy('created_at', 'DESC')->paginate($limit);
    }

    public function findOne($id)
    {
        return $this->serviceRequestEntity->findOrFail($id);
    }

    public function create(ServiceRequestDTO $dto)
    {
        $imageNames = [];

        if ($dto->images) {
            foreach ($dto->images as $image) {
                $uploader = new FileUploader($image, 'uploads/service-requests');

                $uploader->setExtensions(["jpeg", "webp", "png", "jpg"]);
                $uploader->setSizes([
                    "big" => [800, 800],
                    "small" => [150, 150]
                ]);

                $name = $uploader->upload();
                $imageNames[] = $name;
            }
        }

        return $this->serviceRequestEntity->create([
            'full_name'   => $dto->fullName,
            'phone'       => $dto->phone,
            'description' => $dto->description,
            'images'      => $imageNames,
            'service_id'  => $dto->serviceId,
            'is_read'     => false,
        ]);
    }

    public function delete($id)
    {
        $item = $this->findOne($id);
        return $item->delete();
    }

    public function markAsRead($id)
    {
        $item = $this->findOne($id);
        $item->update(['is_read' => true]);
        return $item;
    }
}
