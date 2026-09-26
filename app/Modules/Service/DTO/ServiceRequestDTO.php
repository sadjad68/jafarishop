<?php

namespace App\Modules\Service\DTO;
use Illuminate\Http\Request;

class ServiceRequestDTO
{
    public function __construct(
        public string $fullName,
        public string $phone,
        public ?string $description,
        public array $images,
        public ?int $serviceId,
        public ?bool $is_read,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            fullName: $request->validated('full_name'),
            phone: $request->validated('phone'),
            description: $request->validated('description'),
            images: $request->file('images') ?? [],
            serviceId: $request->validated('service_id'),
            is_read: $request->validated('is_read'),
        );
    }
}
