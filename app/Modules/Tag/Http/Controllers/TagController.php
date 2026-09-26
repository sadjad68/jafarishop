<?php

namespace App\Modules\Tag\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Modules\General\Helper\CacheHelper;
use App\Modules\Tag\DTO\TagDTO;
use App\Modules\Tag\Entities\Tag;
use App\Modules\Tag\Filters\TagFilter;
use App\Modules\Tag\Http\Requests\TagRequest;
use App\Modules\Tag\Services\TagService;

class TagController extends Controller
{
    protected $tagService;
    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }
    /**
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $query = Tag::query();
        if ($request->has(['title'])) {
            $filters = [
                'title' => $request->input('title'),
            ];
            $query = app(TagFilter::class)->apply($query, $filters);
        }
        $tag = $query->orderByDesc('id')->paginate(20);

        return view('admin.tag.index', compact('tag'));

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {

        return view('admin.tag.create');

    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(TagRequest $request)
    {
        //
        $this->tagService->create(TagDTO::fromRequest($request));
        return redirect()->route('admin.tag.index', ['page' => $request->get('fallback_page',1)])
            ->with('success', ' صفحه جدید اضافه شد.');
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data = Tag::findOrfail($id);

        return view('admin.tag.edit', compact('data'));

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(TagRequest $request, $id)
    {
        //
        $this->tagService->update($id, TagDTO::fromRequest($request));
        return redirect()->route('admin.tag.index', ['page' => $request->get('fallback_page',1)])
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
        $this->tagService->destroy($id);
        return Redirect::back()->with('success', 'آیتم موردنظر با موفقیت حذف شد');
    }
    public function sort()
    {
            $tags = $this->tagService->findAll(['list' => true]);

        return view('admin.tag.sort', compact('tags'));

    }
    public function updateSort(Request $request)
    {
        foreach($request->order as $key=>$row){
            $tag = Tag::findOrFail($row);
            $tag->sort = $key+1;
            $tag->save();
        }
        CacheHelper::clearCache();
        echo 'با موفقیت ذخیره شد.';

    }
}
