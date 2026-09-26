<?php

namespace App\Modules\Service\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Modules\Service\Services\ServiceServiceRequest;
use App\Modules\Service\Http\Requests\ServiceRequestRequest;
use App\Modules\Service\DTO\ServiceRequestDTO;

class ServiceRequestController extends Controller
{
    protected ServiceServiceRequest $service;

    public function __construct(ServiceServiceRequest $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $requests = $this->service->findAll();
        return view('admin.service.service-request.index', compact('requests'));
    }

    public function store(ServiceRequestRequest $request)
    {
        $dto = ServiceRequestDTO::fromRequest($request);
        $this->service->create($dto);
        return redirect()->back()->with('success', 'درخواست شما با موفقیت ثبت شد.');
    }

    public function show($id)
    {
        $data = $this->service->findOne($id);
        $this->service->markAsRead($id);
        return view('admin.service.service-request.show', compact('data'));
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return Redirect::back()->with('success', 'درخواست مورد نظر حذف شد.');
    }
}
