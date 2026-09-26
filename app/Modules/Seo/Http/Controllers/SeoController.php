<?php

namespace App\Modules\Seo\Http\Controllers;

use App\Modules\General\Helper\CacheHelper;
use App\Modules\Seo\DTO\SeoDTO;
use App\Modules\Seo\Entities\SeoMeta;
use App\Modules\Seo\Http\Requests\SeoRequest;
use App\Modules\Seo\Http\Requests\StaticSeoRequest;
use App\Modules\Seo\Services\SeoService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SeoController extends Controller
{
    protected $SeoService;
    public function __construct(SeoService $seoService)
    {
        $this->seoService = $seoService;
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(SeoRequest $request)
    {
        $this->seoService->create(SeoDTO::fromRequest($request));
        CacheHelper::clearCache();
        return redirect()->back()->with('success', 'اطلاعات سئو ذخیره شد.');
    }
    public function index()
    {

        $data = SeoMeta::orderBy('id','desc')->whereNotNull('url')->get();
        $index_count = SeoMeta::where('noindex',1)->orderBy('id','desc')->count();
        return view('admin.seo.seo.index', compact('data','index_count'));
    }
    public function edit(int $id)
    {
        $data = SeoMeta::findOrFail($id);
        return view('admin.seo.seo.edit', compact('data'));
    }
    public function update(StaticSeoRequest $request, int $id)
    {
        $this->seoService->update($id, SeoDTO::fromStaticRequest($request));
        CacheHelper::clearCache();
        return redirect()->route('admin.seo.index')->with('success', 'آیتم ویرایش شد.');
    }
    public function indexAll()
    {
        $this->seoService->indexAll();
        CacheHelper::clearCache();
        return redirect()->route('admin.seo.index')->with('success', 'آیتم ویرایش شد.');
    }
}
