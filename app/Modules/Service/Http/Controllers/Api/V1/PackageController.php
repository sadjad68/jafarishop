<?php

namespace App\Modules\Service\Http\Controllers\Api\V1;


use App\Modules\Service\Http\Resources\PackageCollection;
use App\Modules\Service\Services\PackageService;
use Illuminate\Http\JsonResponse;

class PackageController

{
    public function __construct(
        PackageService         $packageService,
    )
    {
        $this->packageService = $packageService;
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function getList(): JsonResponse
    {

        $found_packages = $this->packageService->findAll();
        $packageCollection = new PackageCollection($found_packages);
        $packageCollection->setHiddenFields(['description']);
        $packages = $packageCollection->toArray(request());


        return response()->json([
            'data' => compact(
                'packages',
            ),
            'success' => true
        ]);
    }
    public function getDetail($url): JsonResponse
    {
        $found_package = $this->packageService->findOne($url);
            $packageCollection = new PackageCollection($found_package);
            $package = $packageCollection->transformItemToArray($found_package);
            return response()->json([
                'data' => compact(
                    'package',
                ),
                'success' => true
            ]);
        }

}
