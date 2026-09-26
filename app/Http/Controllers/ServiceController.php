<?php

namespace App\Http\Controllers;

use App\Library\Assistant\Modules\V1\Service;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Blog\Services\BlogService;
use App\Modules\Service\DTO\ServiceRequestDTO;
use App\Modules\Service\Http\Requests\ServiceRequest;
use App\Modules\Service\Http\Requests\ServiceRequestRequest;
use App\Modules\Service\Services\ServiceManager;
use App\Modules\Service\Services\ServiceServiceRequest;
use App\Modules\Service\Services\WorkSampleService;

class ServiceController extends Controller
{
    public function index()
    {
        $query = ['list' => true];
        $services = ServiceManager::findAll($query, false);
        return view('pages.service-list.index', compact(
            'services'
        ));
    }

    public function detail($url)
    {
        $service = ServiceManager::findOne($url);
        if (count($service->children) > 0) {
            $services = $service->children;
            //comments
            $comments = $service->comments;
            //faqs
            $faqs = $service->faqs;
            return view('pages.service-list.index', compact('service', 'services','faqs','comments'));
        } else {
            //blogs
            $query = ['service' => true, 'specific_id' => $service['id']];
            $blogs = BlogService::findAll($query, false);
            //related_service
            $query2 = ['related' => true, 'parent_id' => $service['parent_id']];
            $related_services = ServiceManager::findAll($query2, false, $service['id']);
            //samples
            $query3 = ['related' => true, 'specific_id' => $service['id']];
            $samples = WorkSampleService::findAll($query3);
            //comments
            $comments = $service->comments;
            //fees
            $fees = $service->fees;
            //faqs
            $faqs = $service->faqs;
            return view('pages.service-detail.index',
                compact('service', 'blogs', 'related_services',
                    'samples', 'comments', 'fees','faqs'
                ));
        }
    }

    public function serviceRequest(ServiceRequestRequest $request, ServiceServiceRequest $serviceRequestService)
    {
        $dto = ServiceRequestDTO::fromRequest($request);
        $serviceRequestService->create($dto);
        return redirect()->back()->with('success', "درخواست شما با موفقیت ثبت شد.");
    }
}
