<?php
namespace App\Modules\Course\Services;

use App\Modules\Course\DTO\CourseDTO;
use App\Modules\Course\Entities\Course;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;

class CourseService
{
    protected $model;

    public function __construct(Course $model)
    {
        $this->model = $model;
    }

    public function create(CourseDTO $CourseDTO)
    {
        if ($CourseDTO->image != null) {
            $pathMain = "assets/uploads/Course/";
            $extension = $CourseDTO->image->getClientOriginalExtension();
            $fileName = md5(microtime()) . ".$extension";
            $CourseDTO->image->move($pathMain, $fileName);
            $imageName = $fileName;
        } else {
            $imageName = null;
        }
        $service = $this->model::create([
            'title' => $CourseDTO->title,
            'description' => $CourseDTO->description,
            'teacher_id' => $CourseDTO->teacher_id,
            'course_category_id' => $CourseDTO->course_category_id,
            'price' => $CourseDTO->price,
            'discounted_price' => $CourseDTO->discounted_price,
            'image' => $imageName,
            'url' => $CourseDTO->url,
            'h1' => $CourseDTO->h1,
            'hours' => $CourseDTO->hours,
            'minutes' => $CourseDTO->minutes,
            'type' => $CourseDTO->type,
            'active' => $CourseDTO->active,

        ]);

    }
    public function update(int $id, CourseDTO $CourseDTO)
    {
        $Service = $this->model::findOrfail($id);
        if ($CourseDTO->image != null) {
            File::delete('assets/uploads/Course/' . $Service->image);
            $pathMain = "assets/uploads/Course/";
            $extension = $CourseDTO->image->getClientOriginalExtension();
            $ext = ['jpg', 'jpeg', 'png', 'xls', 'mp3', 'ogg'];
            if (true) {
                $fileName = md5(microtime()) . ".$extension";
                $CourseDTO->image->move($pathMain, $fileName);
                $imageName = $fileName;
            } else {
                return Redirect::back()->with('error', 'فایل ارسالی صحیح نیست');
            }
        } else {
            $imageName = $Service->image;
        }
        $Service->update([
            'title' => $CourseDTO->title,
            'description' => $CourseDTO->description,
            'teacher_id' => $CourseDTO->teacher_id,
            'course_category_id' => $CourseDTO->course_category_id,
            'price' => $CourseDTO->price,
            'discounted_price' => $CourseDTO->discounted_price,
            'image' => $imageName,
            'url' => $CourseDTO->url,
            'h1' => $CourseDTO->h1,
            'hours' => $CourseDTO->hours,
            'minutes' => $CourseDTO->minutes,
            'type' => $CourseDTO->type,
            'active' => $CourseDTO->active,

        ]);

    }
    public function destroy(int $id)
    {
        $Service = $this->model::find($id);
        $this->model::destroy($id);
    }
}
