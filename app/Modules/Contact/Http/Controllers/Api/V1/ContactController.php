<?php

namespace App\Modules\Contact\Http\Controllers\Api\V1;
use App\Modules\Contact\DTO\ContactDTO;
use App\Modules\Contact\Http\Requests\ContactRequest;
use App\Modules\Contact\Services\ContactService;
use Illuminate\Http\JsonResponse;

class ContactController

{
    public function __construct(
        ContactService         $contactService,
    )
    {
        $this->contactService = $contactService;
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function create(ContactRequest $request): JsonResponse
    {
        $this->contactService->create(ContactDTO::fromRequest($request));
        return response()->json([
            'success' => true
        ]);
    }


}
