<?php

namespace App\Modules\Banner\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Modules\Banner\DTO\BannerDTO;
use App\Modules\Banner\Entities\Banner;
use App\Modules\Banner\Http\Requests\BannerRequest;
use App\Modules\Banner\Services\BannerService;
use App\Modules\General\Helper\CacheHelper;

class BannerController extends Controller
{
    protected $bannerService;

    public function __construct(BannerService $bannerService)
    {
        $this->bannerService = $bannerService;
    }

    public function index(Request $request)
    {
        $query = Banner::query();
        $banner = $query->orderByDesc('id')->paginate(20);
        return view('admin.banner.index', compact('banner'));
    }

    public function create()
    {
        return view('admin.banner.create');
    }

    public function store(BannerRequest $request)
    {
        $this->bannerService->create(BannerDTO::fromRequest($request));
        CacheHelper::clearCache();
        return redirect()
            ->route('admin.banner.index', ['page' => $request->get('fallback_page', 1)])
            ->with('success', 'بنر جدید اضافه شد.');
    }

    public function edit($id)
    {
        $data = Banner::findOrfail($id);
        return view('admin.banner.edit', compact('data'));
    }

    public function update($id, BannerRequest $request)
    {
        $this->bannerService->update($id, BannerDTO::fromRequest($request));
        CacheHelper::clearCache();
        return redirect()->route('admin.banner.index', ['page' => $request->get('fallback_page', 1)])
            ->with('success', 'بنر ویرایش شد.');
    }

    public function destroy($id)
    {
        $this->bannerService->destroy($id);
        CacheHelper::clearCache();
        return Redirect::back()->with('success', 'آیتم موردنظر با موفقیت حذف شد');
    }


    //sort
    public function sort()
    {
        $banners = $this->bannerService->findAll(['list' => true]);
        return view('admin.banner.sort', compact('banners'));
    }

    public function updateSort(Request $request)
    {
        foreach ($request->order as $key => $row) {
            $banner = Banner::findOrFail($row);
            $banner->sort = $key + 1;
            $banner->save();
        }
        CacheHelper::clearCache();
        echo 'با موفقیت ذخیره شد.';
    }
}
