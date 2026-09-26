<?php

namespace App\Modules\Setting\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Modules\Gallery\Filters\GalleryFilter;
use App\Modules\Setting\DTO\PartialDTO;
use App\Modules\Setting\Entities\SettingPartial;
use App\Modules\Setting\Http\Requests\PartialRequest;
use App\Modules\Setting\Services\PartialService;

class PartialController extends Controller
{
    protected $partialService;
    public function __construct(PartialService $partialService)
    {
        $this->partialService = $partialService;
    }
    /**
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $query = SettingPartial::query();
        if ($request->has(['title', 'parent_id'])) {
            $filters = [
                'title' => $request->input('title'),
                'parent_id' => $request->input('parent_id'),
            ];
            $query = app(GalleryFilter::class)->apply($query, $filters);
        }
        $partial = $query->orderBy('id', 'DESC')->paginate(20);
        return view('admin.setting.setting-partial.index', compact('partial'));

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $partials = SettingPartial::orderBy('id', 'DESC')->get();
        return view('admin.setting.setting-partial.create', compact('partials'));

    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(PartialRequest $request)
    {

        $this->partialService->create(PartialDTO::fromRequest($request));
        return redirect()->route('admin.setting-partial.index')
            ->with('success', 'آیتم جدید اضافه شد.');
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data = SettingPartial::findOrfail($id);
        if (count($data->children) > 0) {
            $partials = SettingPartial::orderBy('id', 'DESC')->whereNotIn('id', [$id, $data->children->pluck('id')])->get();

        } else {
            $partials = SettingPartial::orderBy('id', 'DESC')->where('id', '<>', $id)->get();

        }

        return view('admin.setting.setting-partial.edit', compact('partials', 'data'));

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(PartialRequest $request, $id)
    {
        $this->partialService->update($id, PartialDTO::fromRequest($request));
        return redirect()->route('admin.setting-partial.index')
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
        $this->partialService->destroy($id);
        return Redirect::back()->with('success', 'آیتم موردنظر با موفقیت حذف شد');
    }
}
