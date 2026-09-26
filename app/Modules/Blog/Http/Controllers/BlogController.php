<?php

namespace App\Modules\Blog\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Modules\Blog\DTO\BlogDTO;
use App\Modules\Blog\Entities\Blog;
use App\Modules\Blog\Entities\BlogCategory;
use App\Modules\Blog\Filters\BlogFilter;
use App\Modules\Blog\Http\Requests\BlogRequest;
use App\Modules\Blog\Services\BlogService;
use App\Modules\General\Helper\FileUploader;
use App\Modules\General\Helper\Test\UploadImg;
use App\Modules\Service\Entities\Service;
use App\Modules\Service\Filters\ServiceFilter;
use Illuminate\Validation\ValidationException;

class BlogController extends Controller
{
    protected $blogService;
    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }
    /**
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $query = Blog::with(['category', 'services'])->where(function ($query) {
            $query->whereDoesntHave('category')
                ->orWhereHas('category', function ($category) {
                    $category->where('type', BlogCategory::TYPE_TEXT);
                });
        });
        if ($request->hasAny(['title', 'url'])) {
            $filters = [
                'title' => $request->input('title'),
                'url' => $request->input('url'),
            ];
            $query = app(BlogFilter::class)->apply($query, $filters);
        }
        $blog = $query->orderby('id', 'DESC')->paginate(20);
        $category = BlogCategory::query()->where('type', BlogCategory::TYPE_TEXT)->orderby('id', 'DESC')->get();

        return view('admin.blog.blog.index', compact('blog', 'category'));

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $category = BlogCategory::query()->where('type', BlogCategory::TYPE_TEXT)->orderby('id', 'DESC')->get();
        $services = Service::orderBy('id', 'DESC')->select(['title', 'id'])->get();
        return view('admin.blog.blog.create', compact('category', 'services'));

    }
    public function test()
    {

        return view('admin.blog.blog.test');

    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(BlogRequest $request)
    {
        $this->assertTextCategory($request->input('parent_id'));
        $this->blogService->create(BlogDTO::fromRequest($request));
        return redirect()->route('admin.blog.index', ['page' => $request->get('fallback_page',1)])
            ->with('success', ' مطلب جدید اضافه شد.');
    }
    public function storeTest(Request $request)
    {
        $uploader = new FileUploader($request->file('image'), "uploads/blog");
        $uploader->setExtensions(["jpeg", "webp", "png", "jpg"]);
        $uploader->setSizes(["big" => [1000, 1000], "small" => [100, 100]]);
        $image = $uploader->upload();
//        if ($request->hasFile('image')) {
//            $path = "uploads/blog";
//            $uploader = new UploadImg();
//            $fileName = $uploader->uploadPic($request->file('image'), $path);
//            if($fileName){
//                $input['image'] = $fileName;
//            }
//        }
        dd($image);
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data = Blog::query()->where(function ($query) {
            $query->whereDoesntHave('category')
                ->orWhereHas('category', function ($category) {
                    $category->where('type', BlogCategory::TYPE_TEXT);
                });
        })->findOrFail($id);
        $category = BlogCategory::query()->where('type', BlogCategory::TYPE_TEXT)->orderby('id', 'DESC')->get();
        $services = Service::orderBy('id', 'DESC')->select(['title', 'id'])->get();
        $selected_services = $data->services;
        return view('admin.blog.blog.edit', compact('category', 'data', 'services', 'selected_services'));

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(BlogRequest $request, $id)
    {
        Blog::query()->where(function ($query) {
            $query->whereDoesntHave('category')
                ->orWhereHas('category', function ($category) {
                    $category->where('type', BlogCategory::TYPE_TEXT);
                });
        })->findOrFail($id);
        $this->assertTextCategory($request->input('parent_id'));
        $this->blogService->update($id, BlogDTO::fromRequest($request));
        return redirect()->route('admin.blog.index', ['page' => $request->get('fallback_page',1)])
            ->with('success', 'مطلب ویرایش شد.');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        Blog::query()->where(function ($query) {
            $query->whereDoesntHave('category')
                ->orWhereHas('category', function ($category) {
                    $category->where('type', BlogCategory::TYPE_TEXT);
                });
        })->findOrFail($id);
        $this->blogService->destroy($id);
        return Redirect::back()->with('success', 'آیتم موردنظر با موفقیت حذف شد');
    }

    private function assertTextCategory($parentId): void
    {
        if ($parentId === null || $parentId === '') {
            return;
        }

        $isVideo = BlogCategory::query()
            ->where('id', $parentId)
            ->where('type', BlogCategory::TYPE_VIDEO)
            ->exists();

        if ($isVideo) {
            throw ValidationException::withMessages([
                'parent_id' => 'این دسته‌بندی مخصوص ویدیوها است.',
            ]);
        }
    }
}
