<?php

namespace App\Modules\Banner\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use App\Modules\Banner\DTO\HighlightDTO;
use App\Modules\Banner\Entities\Highlight;
use App\Modules\Banner\Http\Requests\HighlightRequest;
use App\Modules\Banner\Services\HighlightService;
use App\Modules\General\Helper\CacheHelper;
use App\Modules\Tag\Entities\Tag;

class HighlightController extends Controller
{
    protected $highlightService;

    public function __construct(HighlightService $highlightService)
    {
        $this->highlightService = $highlightService;
    }

    public function index(Request $request)
    {
        $banner = Highlight::query()->with('tag')->orderByDesc('id')->paginate(20);
        return view('admin.banner.highlight.index', compact('banner'));
    }

    public function create()
    {
        $devices = Config::get('banner.devices');
        $tags = Tag::query()->orderBy('sort')->orderBy('id')->get();

        return view('admin.banner.highlight.create', compact('devices', 'tags'));
    }

    public function store(HighlightRequest $request)
    {
        $this->highlightService->create(HighlightDTO::fromRequest($request));
        CacheHelper::clearCache();
        return redirect()
            ->route('admin.highlight.index', ['page' => $request->get('fallback_page', 1)])
            ->with('success', 'بنر جدید اضافه شد.');
    }

    public function edit($id)
    {
        $data = Highlight::findOrfail($id);
        $devices = Config::get('banner.devices');
        $tags = Tag::query()->orderBy('sort')->orderBy('id')->get();

        return view('admin.banner.highlight.edit', compact('data', 'devices', 'tags'));
    }

    public function update($id, HighlightRequest $request)
    {
        $this->highlightService->update($id, HighlightDTO::fromRequest($request));
        CacheHelper::clearCache();
        return redirect()->route('admin.highlight.index', ['page' => $request->get('fallback_page', 1)])
            ->with('success', 'بنر ویرایش شد.');
    }

    public function destroy($id)
    {
        $this->highlightService->destroy($id);
        CacheHelper::clearCache();
        return Redirect::back()->with('success', 'آیتم موردنظر با موفقیت حذف شد');
    }
}
