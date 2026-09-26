<?php

namespace App\Modules\Seo\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Modules\General\Helper\CacheHelper;
use App\Modules\Seo\DTO\CanonicalDTO;
use App\Modules\Seo\Entities\Canonical;
use App\Modules\Seo\Filters\CanonicalFilter;
use App\Modules\Seo\Http\Requests\CanonicalRequest;
use App\Modules\Seo\Services\CanonicalService;

class CanonicalController extends Controller
{
    protected $canonicalService;
    public function __construct(CanonicalService $canonicalService)
    {
        $this->canonicalService = $canonicalService;
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */

    public function index(Request $request)
    {
        $query = Canonical::query();
        if ($request->has(['filter'])) {
            $filters = [
                'url' => $request->get('url'),
                'canonical' => $request->get('canonical'),
            ];
            $query = app(CanonicalFilter::class)->apply($query, $filters);
        }
        $canonical = $query->orderby('id', 'DESC')->paginate(20);
        return view('admin.seo.canonical.index', compact('canonical'));
    }
    public function create()
    {
        return view('admin.seo.canonical.create');

    }
    public function store(CanonicalRequest $request)
    {
        $this->canonicalService->create(CanonicalDTO::fromRequest($request));
        CacheHelper::clearCache();
        return redirect()->route('admin.canonical.index', ['page' => $request->get('fallback_page',1)])->with('success', 'کنونیکال ذخیره شد.');
    }
    public function edit($id)
    {
        $data = Canonical::findOrfail($id);
        return view('admin.seo.canonical.edit', compact('data'));

    }
    public function update(CanonicalRequest $request, $id)
    {
        //
        $this->canonicalService->update($id, CanonicalDTO::fromRequest($request));
        CacheHelper::clearCache();
        return redirect()->route('admin.canonical.index', ['page' => $request->get('fallback_page',1)])
            ->with('success', 'کنونیکال ویرایش شد.');
    }
    public function destroy($id)
    {
        //
        $this->canonicalService->destroy($id);
        CacheHelper::clearCache();
        return Redirect::back()->with('success', 'آیتم موردنظر با موفقیت حذف شد');
    }

}
