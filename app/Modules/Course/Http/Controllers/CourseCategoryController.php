<?php

namespace App\Modules\Course\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use App\Modules\Course\DTO\CourseCategoryDTO;
use App\Modules\Course\Entities\CourseCategory;
use App\Modules\Course\Filters\CourseCategoryFilter;
use App\Modules\Course\Http\Requests\CourseCategoryRequest;
use App\Modules\Course\Services\CourseCategoryService;

class CourseCategoryController extends Controller
{
    protected $CourseCategoryService;
    public function __construct(CourseCategoryService $CourseCategoryService)
    {
        $this->CourseCategoryService = $CourseCategoryService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = CourseCategory::query();
        if ($request->has(['title'])) {
            $filters = [
                'title' => $request->input('title'),
            ];
            $query = app(CourseCategoryFilter::class)->apply($query, $filters);
        }
        $course_category = $query->orderByDesc('id')->paginate(20);
        return view('admin.course.course-category.index', compact('course_category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.course.course-category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CourseCategoryRequest $request)
    {
        $this->CourseCategoryService->create(new CourseCategoryDTO(
            $request['title'],
            $request['description'],
            $request['url'],
            $request['image'],
            $request->has('active'),
        ));
        return Redirect::action([CourseCategoryController::class, 'index'])->with('success', 'آیتم جدید اضافه شد.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = CourseCategory::findOrfail($id);
        return view('admin.course.course-category.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CourseCategoryRequest $request, $id)
    {
        $this->CourseCategoryService->update($id, new CourseCategoryDTO(
            $request['title'],
            $request['description'],
            $request['url'],
            $request['image'],
            $request->has('active'),
        ));
        return Redirect::action([CourseCategoryController::class, 'index'])->with('success', 'آیتم ویرایش شد.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->CourseCategoryService->destroy($id);
        return Redirect::back()->with('success', 'آیتم موردنظر با موفقیت حذف شد');
    }
}
