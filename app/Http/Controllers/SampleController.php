<?php

namespace App\Http\Controllers;

use App\Library\Assistant\Modules\V1\Portfolio;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Modules\Blog\Services\BlogService;
use App\Modules\Service\Services\ServiceManager;
use App\Modules\Service\Services\WorkSampleService;

class SampleController extends Controller
{
    public function index()
    {
        $samples = WorkSampleService::findAll([], 6);
        $services = ServiceManager::findParentsWithSampleChildren()->map(function ($service) {
            return [
                'id' => $service->id,
                'title' => $service->title,
            ];
        })->values();
        return view('pages.sample-list.index', compact('samples', 'services'));
    }
    public function getServiceForFilter(): JsonResponse
    {
        $query = ['list' => true];
        $services = ServiceManager::findAll($query, false);
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
        $samples = WorkSampleService::findPaginate($query, $limit);
        $pageCount = $samples->lastPage();
        $currentPage = $samples->currentPage();
        return response()->json([
            'data' => compact(
                'samples',
                'pageCount',
                'currentPage',
            ),
            'success' => true,
        ]);
    }
    public function detail($url)
    {
      
        $sample = WorkSampleService::findOne($url);
        //related_samples
        $query = ['related' => true, 'specific_id' => $sample->services->pluck('id')->toArray()];
        $related_samples = WorkSampleService::findAll($query, null, $sample['id']);
        //related_blogs
        $query2 = ['sample' => true, 'specific_id' => $sample->services->pluck('id')->toArray()];
        $related_blogs = BlogService::findAll($query2);
        //comments
        $comments = $sample->comments;

        return view('pages.sample-detail.index', compact('sample', 'related_samples',
            'related_blogs', 'comments'));
    }
}
