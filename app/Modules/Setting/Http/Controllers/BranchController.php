<?php

namespace App\Modules\Setting\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Modules\General\Helper\CacheHelper;
use App\Modules\Setting\DTO\BranchDTO;
use App\Modules\Setting\Entities\Branch;
use App\Modules\Setting\Filters\BranchFilter;
use App\Modules\Setting\Http\Requests\BranchRequest;
use App\Modules\Setting\Services\BranchService;

class BranchController extends Controller
{
    protected $branchService;
    public function __construct(BranchService $branchService)
    {
        $this->branchService = $branchService;
    }
    /**
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $query = Branch::query();
        if ($request->has(['title', 'parent_id'])) {
            $filters = [
                'title' => $request->input('title'),
                'parent_id' => $request->input('parent_id'),
            ];
            $query = app(BranchFilter::class)->apply($query, $filters);
        }
        $branch = $query->orderBy('id', 'DESC')->paginate(20);
        return view('admin.setting.branch.index', compact('branch'));

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('admin.setting.branch.create');

    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(BranchRequest $request)
    {
        $this->branchService->create(BranchDTO::fromRequest($request));
        CacheHelper::clearCache();
        return redirect()->route('admin.branch.index', ['page' => $request->get('fallback_page',1)])
            ->with('success', 'شعبه جدید اضافه شد.');

    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data = Branch::findOrfail($id);

        return view('admin.setting.branch.edit', compact('data'));

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(BranchRequest $request, $id)
    {

        //
        $this->branchService->update($id, BranchDTO::fromRequest($request));
        CacheHelper::clearCache();
        return redirect()->route('admin.branch.index', ['page' => $request->get('fallback_page',1)])
            ->with('success', 'شعبه جدید اضافه شد.');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
        $this->branchService->destroy($id);
        CacheHelper::clearCache();
        return Redirect::back()->with('success', 'آیتم موردنظر با موفقیت حذف شد');
    }
}
