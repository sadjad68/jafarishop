<?php

namespace App\Modules\Service\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Modules\Service\DTO\WorkSampleDTO;
use App\Modules\Service\Entities\Service;
use App\Modules\Service\Entities\WorkSample;
use App\Modules\Service\Entities\WorkSampleImage;
use App\Modules\Service\Filters\WorkSampleFilter;
use App\Modules\Service\Http\Requests\WorkSampleRequest;
use App\Modules\Service\Services\WorkSampleService;

class WorkSampleController extends Controller
{
    protected $workSampleService;

    public function __construct(WorkSampleService $workSampleService)
    {
        $this->workSampleService = $workSampleService;
    }
    public function index(Request $request)
    {
        $query = WorkSample::query();
        if ($request->has(['title'])) {
            $filters = [
                'title' => $request->input('title'),
                'service_id' => $request->input('service_id'),
            ];
            $query = app(WorkSampleFilter::class)->apply($query, $filters);
        }
        $samples = $query->orderBy('id', 'DESC')->paginate(20);
        $services = Service::orderBy('id', 'DESC')->select(['title', 'id'])->get();
        $service = [];

        if ($request->get('service_id') != null) {
            $service = Service::find($request->get('service_id'));
            $samples_ids = $service->samples->pluck('id');
            $samples = $query->orderBy('id', 'DESC')->whereIn('id', $samples_ids)->paginate(30);
        }
        return view('admin.service.worksample.index', compact('samples', 'service', 'services'));
    }

    public function create(Request $request)
    {
        $service = Service::find($request->get('service_id'));
        $services = Service::orderBy('id', 'DESC')->select(['title', 'id'])->get();
        return view('admin.service.worksample.create', compact('services', 'service'));
    }

    public function store(WorkSampleRequest $request)
    {
        $this->workSampleService->create(WorkSampleDTO::fromRequest($request));
        return redirect()->route('admin.worksample.index', ['page' => $request->get('fallback_page',1)])
            ->with('success', 'نمونه کار جدید اضافه شد.');

    }

    public function edit($id)
    {
        $data = WorkSample::findOrfail($id);
        $services = Service::orderBy('id', 'DESC')->select(['title', 'id'])->get();
        $selected_services_ids = $data->services->pluck('id')->toArray();
        $selected_services = $data->services;
        return view('admin.service.worksample.edit', compact('data', 'services', 'selected_services_ids', 'selected_services'));
    }

    public function update(WorkSampleRequest $request, $id)
    {
        $this->workSampleService->update($id, WorkSampleDTO::fromRequest($request));
        return redirect()->route('admin.worksample.index', ['page' => $request->get('fallback_page',1)])
            ->with('success', 'نمونه کار ویرایش شد.');
    }

    public function destroy($id)
    {
        //
        WorkSample::destroy($id);
        return Redirect::back()->with('success', 'آیتم موردنظر با موفقیت حذف شد');
    }

    public function images($id)
    {
        $data = WorkSample::findOrfail($id);
        $multiple = true;
        return view('admin.service.worksample.image', compact('data', 'multiple'));
    }

    public function createImage($id, Request $request)
    {
        $double = $request->has('double_image');
        $this->WorkSampleImageService->create($request->file('image'), $id, $double);
        return Redirect::back()->with('success', 'آیتم جدید اضافه شد.');
    }

    public function thumbnail($id)
    {
        $image = WorkSampleImage::findOrfail($id);
        foreach ($image->sample->images as $row) {
            $row->update([
                'thumbnail' => 0,
            ]);
        }
        $image->update([
            'thumbnail' => 1,
        ]);
        return Redirect::back()->with('success', 'تصویر مشخصه با موفقیت ویرایش شد');
    }

    public function deleteImage($id)
    {
        WorkSampleImage::destroy($id);
        return Redirect::back()->with('success', 'تصویر موردنظر با موفقیت حذف شد');
    }
}
