<?php
namespace App\Modules\Course\Services;

use App\Modules\Course\DTO\SessionDTO;
use App\Modules\Course\Entities\Session;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;

class SessionService
{
    protected $model;

    public function __construct(Session $model)
    {
        $this->model = $model;
    }

    public function create(SessionDTO $SessionDTO)
    {

        if ($SessionDTO->thumbnail != null) {
            $pathMain = "assets/uploads/Session/";
            $extension = $SessionDTO->thumbnail->getClientOriginalExtension();
            $fileName = md5(microtime()) . ".$extension";
            $SessionDTO->thumbnail->move($pathMain, $fileName);
            $imageName = $fileName;
        } else {
            $imageName = null;
        }
        $service = $this->model::create([
            'title' => $SessionDTO->title,
            'description' => $SessionDTO->description,
            'thumbnail' => $imageName,
            'hours' => $SessionDTO->hours,
            'minutes' => $SessionDTO->minutes,
            'free' => $SessionDTO->free? true : false,
            'active' => $SessionDTO->active,
            'course_id' => $SessionDTO->course_id,
        ]);

        if ($SessionDTO->files!= null) {
            foreach ($SessionDTO->files as $file) {
                $pathMain = "assets/uploads/Session/";
                $extension = $file->getClientOriginalExtension();
                $orgname = $file->getClientOriginalName();
                $fileName = md5(microtime()). ".$orgname";
                $file->move($pathMain, $fileName);
                $service->sessionFiles()->create([
                    'file' => $fileName,
                ]);
            }
        }
    }
    public function update(int $id, SessionDTO $SessionDTO)
    {
        $service = $this->model::findOrfail($id);
        if ($SessionDTO->thumbnail != null) {
            File::delete('assets/uploads/Session/' . $service->thumbnail);
            $pathMain = "assets/uploads/Session/";
            $extension = $SessionDTO->thumbnail->getClientOriginalExtension();
            $ext = ['jpg', 'jpeg', 'png', 'xls', 'mp3', 'ogg'];
            if (true) {
                $fileName = md5(microtime()) . ".$extension";
                $SessionDTO->thumbnail->move($pathMain, $fileName);
                $imageName = $fileName;
            } else {
                return Redirect::back()->with('error', 'فایل ارسالی صحیح نیست');
            }
        } else {
            $imageName = $service->thumbnail;
        }
        $service->update([
            'title' => $SessionDTO->title,
            'description' => $SessionDTO->description,
            'thumbnail' => $imageName,
            'hours' => $SessionDTO->hours,
            'minutes' => $SessionDTO->minutes,
            'free' => $SessionDTO->free,
            'active' => $SessionDTO->active,
            'course_id' => $SessionDTO->course_id,
        ]);

        if ($SessionDTO->files!= null) {
            foreach ($SessionDTO->files as $file) {
                $pathMain = "assets/uploads/Session/";
                // $extension = $file->getClientOriginalExtension();
                $orgname = $file->getClientOriginalName();
                $fileName = md5(microtime()). ".$orgname";
                $file->move($pathMain, $fileName);
                $service->sessionFiles()->create([
                    'file' => $fileName,
                ]);
            }
        }
    }
    public function destroy(int $id)
    {
        $Service = $this->model::find($id);
        $this->model::destroy($id);
    }
}
