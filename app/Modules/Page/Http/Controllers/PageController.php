<?php

namespace App\Modules\Page\Http\Controllers;

use App\Modules\General\Helper\MakeTree;
use App\Modules\Page\DTO\PageDTO;
use App\Modules\Page\Entities\Page;
use App\Modules\Page\Filters\PageFilter;
use App\Modules\Page\Http\Requests\PageRequest;
use App\Modules\Page\Services\PageService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;

class PageController extends Controller
{
    protected $pageService;
    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
    }
    /**
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $query = Page::query();
        if ($request->has(['title'])) {
            $filters = [
                'title' => $request->input('title'),
                'parent_id' => $request->input('parent_id'),
            ];
            $query = app(PageFilter::class)->apply($query, $filters);
        }
        $page = $query->orderby('id', 'DESC')->paginate(20);
        return view('admin.page.index', compact('page'));

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('admin.page.create');

    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(PageRequest $request)
    {
        //
        $this->pageService->create(PageDTO::fromRequest($request));
        return redirect()->route('admin.page.index', ['page' => $request->get('fallback_page',1)])
            ->with('success', ' صفحه جدید اضافه شد.');
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data = Page::findOrfail($id);
        return view('admin.page.edit', compact('data'));

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(PageRequest $request, $id)
    {
        //
        $this->pageService->update($id, PageDTO::fromRequest($request));
        return redirect()->route('admin.page.index', ['page' => $request->get('fallback_page',1)])
            ->with('success', 'صفحه ویرایش شد.');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
        $this->pageService->destroy($id);
        return Redirect::back()->with('success', 'آیتم موردنظر با موفقیت حذف شد');
    }
}
