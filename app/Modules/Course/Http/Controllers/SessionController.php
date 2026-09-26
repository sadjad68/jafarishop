<?php

namespace App\Modules\Course\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Modules\Course\DTO\SessionDTO;
use App\Modules\Course\Entities\Course;
use App\Modules\Course\Entities\Session;
use App\Modules\Course\Filters\SessionFilter;
use App\Modules\Course\Http\Requests\SessionRequest;
use App\Modules\Course\Services\SessionService;

class SessionController extends Controller
{
    protected $SessionService;
    public function __construct(SessionService $SessionService)
    {
        $this->SessionService = $SessionService;
    }
    /**
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $query = Session::query();
        $corse = [];

        if ($request->has(['course_id'])) {
            $corse = Course::findOrfail($request->course_id);
            $filters = [
                // 'title' => $request->input('title'),
                'course_id' => $request->input('course_id'),
            ];
            $query = app(SessionFilter::class)->apply($query, $filters);
        }
        $session = $query->orderByDesc('id')->paginate(20);
        return view('admin.course.session.index', compact('session', 'corse'));

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Request $request, )
    {
        $courses = Course::orderBy('created_at', 'desc')->select('id', 'title')->get();
        $course = Course::find($request->course_id);

        return view('admin.course.session.create', compact('courses', 'course'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(SessionRequest $request)
    {
        $this->SessionService->create(new SessionDTO(
            $request['title'],
            $request['description'],
            $request->has('free'),
            $request->has('active'),
            $request['hours'],
            $request['minutes'],
            $request['image'],
            $request['files'],
            $request['course_id'],
        ));
        return Redirect::action([SessionController::class, 'index'], ['course_id' => $request['course_id']])->with('success', 'آیتم جدید اضافه شد.');
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data = Session::findOrfail($id);
        $courses = Course::orderBy('created_at', 'desc')->select('id', 'title')->get();

        return view('admin.course.session.edit', compact('data', 'courses'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(SessionRequest $request, $id)
    {
        $this->SessionService->update($id, new SessionDTO(
            $request['title'],
            $request['description'],
            $request->has('free'),
            $request->has('active'),
            $request['hours'],
            $request['minutes'],
            $request['image'],
            $request['files'],
            $request['course_id'],
        ));

        return Redirect::action([SessionController::class, 'index'], ['course_id' => $request['course_id']])->with('success', 'آیتم ویرایش شد.');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
        $this->SessionService->destroy($id);
        return Redirect::back()->with('success', 'آیتم موردنظر با موفقیت حذف شد');
    }

    public function updateOrder(Request $request)
    {
        $newOrder = $request->input('order');

        foreach ($newOrder as $index => $itemId) {
            Session::where('id', $itemId)->update(['sort' => $index + 1]);
        }

        return response()->json(['message' => 'ترتیب با موفقیت بروزرسانی شد']);
    }

}
