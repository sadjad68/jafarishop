<?php

namespace App\Modules\Service\Http\Controllers;

use App\Modules\General\Helper\CacheHelper;
use App\Modules\Service\DTO\ServiceDTO;
use App\Modules\Service\Entities\Service;
use App\Modules\Service\Filters\ServiceFilter;
use App\Modules\Service\Http\Requests\ServiceRequest;
use App\Modules\Service\Services\ServiceManager;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;

class ServiceController extends Controller
{
    protected ServiceManager $serviceManager;
    public function __construct(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    public function index(Request $request)
    {
        $query = Service::query();
        if ($request->hasAny(['title', 'parent_id'])) {
            $filters = [
                'title' => $request->get('title'),
                'parent_id' => $request->get('parent_id'),
            ];
            $query = app(ServiceFilter::class)->apply($query, $filters);
            $services = $query->orderByDesc('id')->paginate(15);
        }
        else{
            $services = $query->orderByDesc('id')->get();
            $services = $this->serviceManager->formatServices($services, 15);
        }

        $all_services = $this->serviceManager->findAll();
        return view('admin.service.service.index', compact('services', 'all_services'));
    }

    public function create()
    {
        $select[] = ['id', 'title', 'parent_id'];
        $services = $this->serviceManager->findAll($select);
        CacheHelper::clearCache();
        $description_positions  = [
            ['value' => 'top', 'title' => 'بالا'],
            ['value' => 'bottom', 'title' => 'پایین'],
        ];
        return view('admin.service.service.create', compact('services','description_positions'));
    }

    public function store(ServiceRequest $request)
    {
        $message = $this->serviceManager->create(ServiceDTO::fromRequest($request));
        CacheHelper::clearCache();
        return redirect()->route('admin.service.index', ['page' => $request->get('fallback_page',1)])->with('success', 'آیتم جدید اضافه شد.')
            ->with('info', $message);
    }

    public function edit(int $id)
    {
        $data = Service::findOrFail($id);
        $services = $this->serviceManager->findAll($id);
        $description_positions  = [
            ['value' => 'top', 'title' => 'بالا'],
            ['value' => 'bottom', 'title' => 'پایین'],
        ];
        return view('admin.service.service.edit', compact('data', 'services','description_positions'));
    }

    public function update(ServiceRequest $request, int $id)
    {
        $message = $this->serviceManager->update($id, ServiceDTO::fromRequest($request));
        CacheHelper::clearCache();
        return redirect()->route('admin.service.index', ['page' => $request->get('fallback_page',1)])->with('success', 'آیتم ویرایش شد.')
            ->with('info', $message);
    }

    public function destroy($id)
    {
        $this->serviceManager->deleteOne($id);
        CacheHelper::clearCache();
        return Redirect::back()->with('success', 'آیتم موردنظر با موفقیت حذف شد');
    }

    public function deleteRoot($id)
    {
        $this->serviceManager->deleteRoot($id);
        CacheHelper::clearCache();
        return Redirect::back()->with('success', 'آیتم موردنظر با موفقیت حذف شد');
    }
    public function sort(int $parent_id = null)
    {
        if ($parent_id == null){
            $data  = null;
            $services = $this->serviceManager->findAll(['list' => true],false);
        }
        else{
            $data = Service::findOrfail($parent_id);
            $services = $this->serviceManager->findAll(['parent_id' => $parent_id],false);
        }

        return view('admin.service.service.sort', compact('data','services'));

    }
    public function updateSort(Request $request,int $parent_id = null)
    {
        foreach($request->order as $key=>$row){
            $service = Service::findOrFail($row);
            $service->sort = $key+1;
            $service->save();
        }
        CacheHelper::clearCache();
        echo 'با موفقیت ذخیره شد.';

    }
}
