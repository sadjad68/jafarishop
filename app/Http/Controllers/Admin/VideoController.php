<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Blog\DTO\BlogDTO;
use App\Modules\Blog\Entities\Blog;
use App\Modules\Blog\Entities\BlogCategory;
use App\Modules\Blog\Filters\BlogFilter;
use App\Modules\Blog\Http\Requests\BlogRequest;
use App\Modules\Blog\Services\BlogService;
use App\Modules\Service\Entities\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;

class VideoController extends Controller
{
    public function __construct(protected BlogService $blogService)
    {
    }

    public function index(Request $request)
    {
        $query = $this->videoQuery()->with(['category', 'services']);
        if ($request->hasAny(['title', 'url'])) {
            $filters = [
                'title' => $request->input('title'),
                'url' => $request->input('url'),
            ];
            $query = app(BlogFilter::class)->apply($query, $filters);
        }
        $videos = $query->orderByDesc('id')->paginate(20);

        return view('admin.video.index', compact('videos'));
    }

    public function create()
    {
        $category = $this->videoCategories();
        $services = Service::orderByDesc('id')->select(['title', 'id'])->get();

        return view('admin.video.create', compact('category', 'services'));
    }

    public function store(BlogRequest $request)
    {
        $this->assertVideoCategory($request->input('parent_id'));
        $this->blogService->create(BlogDTO::fromRequest($request));

        return redirect()->route('admin.video.index', ['page' => $request->get('fallback_page', 1)])
            ->with('success', 'ویدیو جدید اضافه شد.');
    }

    public function edit($id)
    {
        $data = $this->videoQuery()->findOrFail($id);
        $category = $this->videoCategories();
        $services = Service::orderByDesc('id')->select(['title', 'id'])->get();

        return view('admin.video.edit', compact('category', 'data', 'services'));
    }

    public function update(BlogRequest $request, $id)
    {
        $this->videoQuery()->findOrFail($id);
        $this->assertVideoCategory($request->input('parent_id'));
        $this->blogService->update($id, BlogDTO::fromRequest($request));

        return redirect()->route('admin.video.index', ['page' => $request->get('fallback_page', 1)])
            ->with('success', 'ویدیو ویرایش شد.');
    }

    public function destroy($id)
    {
        $this->videoQuery()->findOrFail($id);
        $this->blogService->destroy($id);

        return Redirect::back()->with('success', 'آیتم موردنظر با موفقیت حذف شد');
    }

    private function videoQuery()
    {
        return Blog::query()->whereHas('category', function ($query) {
            $query->where('type', BlogCategory::TYPE_VIDEO);
        });
    }

    private function videoCategories()
    {
        return BlogCategory::query()
            ->where('type', BlogCategory::TYPE_VIDEO)
            ->orderByDesc('id')
            ->get();
    }

    private function assertVideoCategory($parentId): void
    {
        $exists = BlogCategory::query()
            ->where('id', $parentId)
            ->where('type', BlogCategory::TYPE_VIDEO)
            ->exists();

        if (!$exists) {
            throw ValidationException::withMessages([
                'parent_id' => 'دسته‌بندی باید از نوع ویدیو باشد.',
            ]);
        }
    }
}
