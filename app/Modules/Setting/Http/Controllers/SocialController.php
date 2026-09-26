<?php

namespace App\Modules\Setting\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Modules\General\Helper\CacheHelper;
use App\Modules\Setting\DTO\SocialDTO;
use App\Modules\Setting\Entities\Social;
use App\Modules\Setting\Filters\SocialFilter;
use App\Modules\Setting\Http\Requests\SocialRequest;
use App\Modules\Setting\Services\SocialService;

class SocialController extends Controller
{
    protected $socialService;
    public function __construct(SocialService $socialService)
    {
        $this->socialService = $socialService;
    }
    /**
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $query = Social::query();
        if ($request->has(['title', 'parent_id'])) {
            $filters = [
                'title' => $request->input('title'),
                'parent_id' => $request->input('parent_id'),
            ];
            $query = app(SocialFilter::class)->apply($query, $filters);
        }
        $social = $query->orderBy('id', 'DESC')->paginate(20);
        return view('admin.setting.social.index', compact('social'));

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('admin.setting.social.create');

    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(SocialRequest $request)
    {

        //
        $this->socialService->create(SocialDTO::fromRequest($request));
        CacheHelper::clearCache();

        return redirect()->route('admin.social.index', ['page' => $request->get('fallback_page',1)])
            ->with('success', 'آیتم جدید اضافه شد.');
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data = Social::findOrfail($id);

        return view('admin.setting.social.edit', compact('data'));

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(SocialRequest $request, $id)
    {

        //
        $this->socialService->update($id, SocialDTO::fromRequest($request));
        CacheHelper::clearCache();
        return redirect()->route('admin.social.index', ['page' => $request->get('fallback_page',1)])
            ->with('success', 'آیتم ویرایش شد.');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
        $this->socialService->destroy($id);
        CacheHelper::clearCache();
        return Redirect::back()->with('success', 'آیتم موردنظر با موفقیت حذف شد');
    }
}
