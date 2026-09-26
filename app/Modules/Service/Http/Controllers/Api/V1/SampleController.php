<?php

namespace App\Modules\Service\Http\Controllers\Api\V1;

use App\Modules\Comment\Http\Resources\CommentCollection;
use App\Modules\Comment\Services\CommentService;
use App\Modules\Service\Http\Resources\ForFilterServiceCollection;
use App\Modules\Service\Services\ServiceManager;
use App\Modules\Blog\Http\Resources\BlogCollection;
use App\Modules\Blog\Services\BlogService;
use App\Modules\Service\Http\Resources\WorkSampleCollection;
use App\Modules\Service\Services\WorkSampleService;
use Illuminate\Http\JsonResponse;

class SampleController
{
    public function __construct(
        ServiceManager $serviceManager,
        WorkSampleService $workSampleService,
        CommentService $commentService,
        BlogService $blogService,

    ) {
        $this->serviceManager = $serviceManager;
        $this->workSampleService = $workSampleService;
        $this->commentService = $commentService;
        $this->blogService = $blogService;

    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function getIndex(): JsonResponse
    {
        $found_samples = $this->workSampleService->findAll([], 6);
        $sampleCollection = new WorkSampleCollection($found_samples);
        $sampleCollection->setHiddenFields(['description', 'short_description']);
        $samples = $sampleCollection->toArray(request());
        $query = ['list' => true];
        $found_service = $this->serviceManager->findAll($query, false);
        $serviceCollection = new ForFilterServiceCollection($found_service);
        $services = $serviceCollection->toArray(request());
        return response()->json([
            'data' => compact(
                'samples',
                'services'
            ),
            'success' => true,
        ]);
    }
    //Todo: توضیح کالکتور
    public function getServiceForFilter(): JsonResponse
    {
        $query = ['list' => true];
        $found_service = $this->serviceManager->findAll($query, false);
        $serviceCollection = new ForFilterServiceCollection($found_service);
        $services = $serviceCollection->toArray(request());
        return response()->json([
            'data' => compact(
                'services'
            ),
            'success' => true,
        ]);
    }
    public function getListForVue(): JsonResponse
    {
        $query = [];
        $limit = 6;
        $serviceId = (int) request()->input('service_id');
        if ($serviceId > 0) {
            $query = ['related' => true, 'specific_id' => $serviceId];
            $limit = null;
        }
        $found_samples = $this->workSampleService->findPaginate($query, $limit);
        $sampleCollection = new WorkSampleCollection($found_samples);
        $sampleCollection->setHiddenFields(['description', 'short_description']);
        $samples = $sampleCollection->toArray(request());
        $pageCount = count($found_samples) > 0 ? $found_samples->lastPage() : 0;
        $currentPage = count($found_samples) > 0 ? $found_samples->currentPage() : 0;
        $lastPage = $found_samples->lastPage();

        return response()->json([
            'data' => compact(
                'samples',
                'pageCount',
                'currentPage',
                'lastPage'
            ),
            'success' => true,
        ]);
    }
    public function getDetail($url): JsonResponse
    {
        $found_sample = $this->workSampleService->findOne($url);
        $sampleCollection = new WorkSampleCollection([$found_sample]);
        $sample = $sampleCollection->transformItemToArray($found_sample);
        //related_samples
        $query = ['related' => true, 'specific_id' => $found_sample->services->pluck('id')->toArray()];
        $sample_formatter = $this->workSampleService->findAll($query, null, $found_sample['id']);
        $relatedSampleCollection = new WorkSampleCollection($sample_formatter);
        $relatedSampleCollection->setHiddenFields(['description', 'images', 'short_description']);
        $related_samples = $relatedSampleCollection->toArray(request());
        //related_blogs
        $query2 = ['sample' => true, 'specific_id' => $found_sample->services->pluck('id')->toArray()];
        $related_blog_formatter = $this->blogService->findAll($query2);
        $relatedBlogCollection = new BlogCollection($related_blog_formatter);
        $relatedBlogCollection->setHiddenFields(['description', 'blog_category_url',
            'blog_category_title',
            'author',
            'header_image',
            'call_to_action',
            'view',
            'reading_time']);
        $related_blogs = $relatedBlogCollection->toArray(request());
        //comments
        $comments = new CommentCollection($found_sample->comments);

        return response()->json([
            'data' => compact(
                'sample',
                'related_samples',
                'related_blogs',
                'comments',
            ),
            'success' => true,
        ]);
    }

}
