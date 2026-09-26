<?php

namespace App\Modules\Seo\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use App\Modules\General\Helper\CacheHelper;
use App\Modules\Product\Http\Requests\ImportRequest;
use App\Modules\Product\Imports\ProductsImport;
use App\Modules\Seo\DTO\RedirectDTO;
use App\Modules\Seo\Filters\RedirectFilter;
use App\Modules\Seo\Http\Requests\RedirectRequest;
use App\Modules\Seo\Imports\RedirectImport;
use App\Modules\Seo\Services\RedirectService;
use \App\Modules\Seo\Entities\Redirect as RD;

class RedirectController extends Controller
{
    protected $redirectService;
    public function __construct(RedirectService $redirectService)
    {
        $this->redirectService = $redirectService;
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */

    public function index(Request $request)
    {
        $query = RD::query();
        if ($request->has(['filter'])) {
            $filters = [
                'old_address' => $request->get('old_address'),
                'new_address' => $request->get('new_address'),
                'type' => $request->get('type'),
            ];
            $query = app(RedirectFilter::class)->apply($query, $filters);
        }
        $redirect = $query->orderby('id', 'DESC')->paginate(20);
        return view('admin.seo.redirect.index', compact('redirect'));
    }

    public function create()
    {
        return view('admin.seo.redirect.create');

    }
    public function store(RedirectRequest $request)
    {
        $check = RD::orderBy('id', 'DESC')->where('old_address', '/' . trim(str_replace(url('/'), "", $request->get('old_address')), '/'))->first();
        if ($check) {
            $check->delete();
        }
        $this->redirectService->create(RedirectDTO::fromRequest($request));
        CacheHelper::clearCache();
        return redirect()->route('admin.redirect.index', ['page' => $request->get('fallback_page',1)])->with('success', 'ریدایرکت ذخیره شد.');
    }
    public function destroy($id)
    {
        $this->redirectService->destroy($id);
        CacheHelper::clearCache();
        return Redirect::back()->with('success', 'آیتم موردنظر با موفقیت حذف شد');
    }
    public function import(ImportRequest $request)
    {

        Excel::import(new RedirectImport(), $request->file('excel'));
        CacheHelper::clearCache();
        return Redirect::back()->with('success', 'با موفقیت اضافه شد');
    }
}
