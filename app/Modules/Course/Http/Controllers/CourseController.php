<?php

namespace App\Modules\Course\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Modules\Course\DTO\CourseDTO;
use App\Modules\Course\Entities\Course;
use App\Modules\Course\Entities\CourseCategory;
use App\Modules\Course\Filters\CourseFilter;
use App\Modules\Course\Http\Requests\CourseRequest;
use App\Modules\Course\Services\CourseService;
use App\Modules\User\Entities\User;

class CourseController extends Controller
{
    protected $CourseService;
    public function __construct(CourseService $CourseService)
    {
        $this->CourseService = $CourseService;
    }
    /**
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $query = Course::query();
        if ($request->has(['title', 'parent_id'])) {
            $filters = [
                'title' => $request->input('title'),
                'parent_id' => $request->input('parent_id'),
            ];
            $query = app(CourseFilter::class)->apply($query, $filters);
        }
        $course = $query->orderByDesc('id')->paginate(20);
        $category = CourseCategory::orderby('id', 'DESC')->get();
        return view('admin.course.course.index', compact('course', 'category'));

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $category = CourseCategory::orderby('id', 'DESC')->get();
        $users = User::orderby('id', 'DESC')->get(); // To Do: add where teacher after set this role in users table
        return view('admin.course.course.create', compact(['category', 'users']));

    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(CourseRequest $request)
    {
        $this->CourseService->create(new CourseDTO(
            $request['title'],
            $request['description'],
            $request['course_category_id'],
            $request['teacher_id'],
            $request['price'],
            $request->has('discounted_price') ? $request['discounted_price'] : null,
            $request['hours'],
            $request['minutes'],
            $request['type'],
            $request['url'],
            $request['h1'],
            $request['image'],
            $request->has('active'),
        ));
        return Redirect::action([CourseController::class, 'index'])->with('success', 'آیتم جدید اضافه شد.');
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data = Course::findOrfail($id);
        $category = CourseCategory::orderby('id', 'DESC')->get();
        $users = User::orderby('id', 'DESC')->get(); // To Do: add where teacher after set this role in users table

        return view('admin.course.course.edit', compact('category', 'data', 'users'));

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(CourseRequest $request, $id)
    {

        $this->CourseService->update($id, new CourseDTO(
            $request['title'],
            $request['description'],
            $request['course_category_id'],
            $request['teacher_id'],
            $request['price'],
            $request->has('discounted_price') ? $request['discounted_price'] : null,
            $request['hours'],
            $request['minutes'],
            $request['type'],
            $request['url'],
            $request['h1'],
            $request['image'],
            $request->has('active'),
        ));
        return Redirect::action([CourseController::class, 'index'])->with('success', 'آیتم ویرایش شد.');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
        $this->CourseService->destroy($id);
        return Redirect::back()->with('success', 'آیتم موردنظر با موفقیت حذف شد');
    }
}
