<?php

namespace App\Modules\Setting\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Modules\Setting\DTO\SloganDTO;
use App\Modules\Setting\Entities\Slogan;
use App\Modules\Setting\Filters\SloganFilter;
use App\Modules\Setting\Http\Requests\SloganRequest;
use App\Modules\Setting\Services\SloganService;

class SloganController extends Controller
{
    protected $sloganService;
    public function __construct(SloganService $sloganService)
    {
        $this->sloganService = $sloganService;
    }
    /**
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $query = Slogan::query();
        if ($request->has(['title', 'parent_id'])) {
            $filters = [
                'title' => $request->input('title'),
                'parent_id' => $request->input('parent_id'),
            ];
            $query = app(SloganFilter::class)->apply($query, $filters);
        }
        $slogan = $query->orderBy('id', 'DESC')->paginate(20);
        return view('admin.setting.slogan.index', compact('slogan'));

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('admin.setting.slogan.create');

    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(SloganRequest $request)
    {

        $this->sloganService->create(SloganDTO::fromRequest($request));
        return redirect()->route('admin.slogan.index', ['page' => $request->get('fallback_page',1)])
            ->with('success', 'شعارها جدید اضافه شد.');
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data = Slogan::findOrfail($id);

        return view('admin.setting.slogan.edit', compact('data'));

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(SloganRequest $request, $id)
    {
        $this->sloganService->update($id, SloganDTO::fromRequest($request));
        return redirect()->route('admin.slogan.index', ['page' => $request->get('fallback_page',1)])
            ->with('success', 'شعارها ویرایش شد.');

    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
        $this->sloganService->destroy($id);
        return Redirect::back()->with('success', 'آیتم موردنظر با موفقیت حذف شد');
    }
}
