<?php

namespace App\Modules\Service\Http\Controllers\Api\V1;

use App\Modules\Comment\Http\Resources\CommentCollection;
use App\Modules\Comment\Services\CommentService;
use App\Modules\Service\Http\Resources\DetailServiceCollection;
use App\Modules\Service\Http\Resources\ServiceCollection;
use App\Modules\Service\Services\ServiceManager;
use App\Modules\Blog\Http\Resources\BlogCollection;
use App\Modules\Blog\Services\BlogService;
use App\Modules\Service\Http\Resources\FeeCollection;
use App\Modules\Service\Http\Resources\WorkSampleCollection;
use App\Modules\Service\Services\WorkSampleService;
use Illuminate\Http\JsonResponse;

class ServiceController
{
    public function __construct(
        ServiceManager $serviceManager,
        WorkSampleService $workSampleService,
        BlogService $blogService,
        CommentService $commentService,
    ) {
        $this->serviceManager = $serviceManager;
        $this->workSampleService = $workSampleService;
        $this->blogService = $blogService;
        $this->commentService = $commentService;
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function getIndex(): JsonResponse
    {
        $query = ['list' => true];
        $found_service = $this->serviceManager->findAll($query, false);
        $serviceCollection = new ServiceCollection($found_service);
        $serviceCollection->setHiddenFields(['children', 'samples', 'parent_url', 'description']);
        $services = $serviceCollection->toArray(request());

        return response()->json([
            'data' => compact(
                'services',
            ),
            'success' => true,
        ]);
    }
    public function getDetail($url): JsonResponse
    {
        $found_service = $this->serviceManager->findOne($url);
        if (count($found_service->children) > 0) {
            $type = 'list';
            $serviceCollection = new ServiceCollection([$found_service]);
            $service = $serviceCollection->transformItemToArray($found_service);
            return response()->json([
                'data' => compact(
                    'service',
                    'type'
                ),
                'success' => true,
            ]);
        } else {
            $serviceCollection = new DetailServiceCollection([$found_service]);
            $service = $serviceCollection->transformItemToArray($found_service);
            $type = 'detail';
            //blogs
            $query = ['service' => true, 'specific_id' => $found_service['id']];
            $blog_formatter = $this->blogService->findAll($query, false);
            $blogCollection = new BlogCollection($blog_formatter);
            $blogs = $blogCollection->toArray(request());
            //related_service
            $query2 = ['related' => true, 'parent_id' => $found_service['parent_id']];
            $related_service_formatter = $this->serviceManager->findAll($query2, false, $found_service['id']);
            $relatedServiceCollection = new ServiceCollection($related_service_formatter);
            $relatedServiceCollection->setHiddenFields(['children', 'samples', 'parent_url', 'description']);
            $related_services = $relatedServiceCollection->toArray(request());
            //samples
            $query3 = ['related' => true, 'specific_id' => $found_service['id']];
            $sampleCollection = new WorkSampleCollection($this->workSampleService->findAll($query3));

            $sampleCollection->setHiddenFields(['description']);
            $samples = $sampleCollection->toArray(request());
            //comments
            $comments = new CommentCollection($found_service->comments);
            //fees
            $feeCollection = new FeeCollection($found_service->fees);
//            $feeCollection->setHiddenFields(['description']);
            $fees = $feeCollection->toArray(request());
            return response()->json([
                'data' => compact(
                    'service',
                    'blogs',
                    'related_services',
                    'samples',
                    'comments',
                    'fees',
                    'type'
                ),
                'success' => true,
            ]);
        }

    }

}
