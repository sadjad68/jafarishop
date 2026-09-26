<?php

namespace App\Modules\Service\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Modules\General\Helper\MakeTree;
use App\Modules\Service\DTO\PackageDTO;
use App\Modules\Service\Entities\Package;
use App\Modules\Service\Entities\Service;
use App\Modules\Service\Filters\PackageFilter;
use App\Modules\Service\Http\Requests\PackageRequest;
use App\Modules\Service\Services\PackageService;

class PackageController extends Controller
{
    protected $packageService;
    public function __construct(PackageService $packageService)
    {
        $this->packageService = $packageService;
    }

    public function index(Request $request)
    {
        $query = Package::query();
        if ($request->has(['title', 'parent_id'])) {
            $filters = [
                'title' => $request->input('title'),
            ];
            $query = app(PackageFilter::class)->apply($query, $filters);
        }
        $package = $query->orderByDesc('id')->paginate(20);
        return view('admin.service.package.index', compact('package'));

    }

    public function create()
    {
        $services = Service::all()->toArray();
        if (!empty($services)) {
            MakeTree::getData($services);
            $services = MakeTree::GenerateArray(array('get'));
        }
        return view('admin.service.package.create', compact('services'));
    }

    public function store(PackageRequest $request)
    {
        //
        $this->packageService->create(PackageDTO::fromRequest($request));
        return redirect()->route('admin.package.index', ['page' => $request->get('fallback_page',1)])
            ->with('success', 'پکیج جدید اضافه شد.');
    }

    public function edit($id)
    {
        $data = Package::findOrfail($id);
        $services = Service::all()->toArray();
        if (!empty($services)) {
            MakeTree::getData($services);
            $services = MakeTree::GenerateArray(array('get'));
        }
        return view('admin.service.package.edit', compact('data', 'services'));
    }

    public function update(PackageRequest $request, $id)
    {
        //
        $this->packageService->update($id, PackageDTO::fromRequest($request));
        return redirect()->route('admin.package.index', ['page' => $request->get('fallback_page',1)])
            ->with('success', 'پکیج ویرایش شد.');
    }

    public function destroy($id)
    {
        //
        $this->packageService->destroy($id);
        return Redirect::back()->with('success', 'آیتم موردنظر با موفقیت حذف شد');
    }
}
