<?php
namespace App\Modules\Course\Services;

use App\Modules\Course\DTO\CourseCategoryDTO;
use App\Modules\Course\Entities\CourseCategory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;

class CourseCategoryService
{
    protected $model;

    public function __construct(CourseCategory $model)
    {
        $this->model = $model;
    }

    public function create(CourseCategoryDTO $CourseCategoryDTO)
    {
        if ($CourseCategoryDTO->image != null) {
            $pathMain = "assets/uploads/Course-category/";
            $extension = $CourseCategoryDTO->image->getClientOriginalExtension();
            $fileName = md5(microtime()) . ".$extension";
            $CourseCategoryDTO->image->move($pathMain, $fileName);
            $imageName = $fileName;
        } else {
            $imageName = null;
        }
        $service = $this->model::create([
            'title' => $CourseCategoryDTO->title,
            'description' => $CourseCategoryDTO->description,
            'url' => $CourseCategoryDTO->url,
            'image' => $imageName,
            'active' => $CourseCategoryDTO->active,

        ]);

    }
    public function update(int $id, CourseCategoryDTO $CourseCategoryDTO)
    {
        $Service = $this->model::findOrfail($id);
        if ($CourseCategoryDTO->image != null) {
            File::delete('assets/uploads/Course-category/' . $Service->image);
            $pathMain = "assets/uploads/Course-category/";
            $extension = $CourseCategoryDTO->image->getClientOriginalExtension();
            $ext = ['jpg', 'jpeg', 'png', 'xls', 'mp3', 'ogg'];
            if (true) {
                $fileName = md5(microtime()) . ".$extension";
                $CourseCategoryDTO->image->move($pathMain, $fileName);
                $imageName = $fileName;
            } else {
                return Redirect::back()->with('error', 'فایل ارسالی صحیح نیست');
            }
        } else {
            $imageName = $Service->image;
        }
        $Service->update([
            'title' => $CourseCategoryDTO->title,
            'description' => $CourseCategoryDTO->description,
            'url' => $CourseCategoryDTO->url,
            'image' => $imageName,
            'active' => $CourseCategoryDTO->active,

        ]);

    }
    public function destroy(int $id)
    {
        $Service = $this->model::find($id);
        $this->model::destroy($id);
    }
}
