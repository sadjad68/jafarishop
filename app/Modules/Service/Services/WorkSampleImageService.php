<?php
namespace App\Modules\Service\Services;

use App\Modules\Service\DTO\WorkSampleImageDTO;
use App\Modules\Service\Entities\WorkSampleImage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;

class WorkSampleImageService
{
    protected $model;

    public function __construct(WorkSampleImage $model)
    {
        $this->model = $model;
    }
    public function create($images, int $WorkSampleId, $double)
    {
        if ($images) {
            $pathMain = "assets/uploads/work-sample/";
            foreach ($images as $image) {
                $extension = $image->getClientOriginalExtension();
                $fileName = md5(microtime()) . ".$extension";
                $image->move($pathMain, $fileName);
                $this->model::create([
                    'work_sample_id' => $WorkSampleId,
                    'image' => $fileName,
                    'double_image' => $double,
                ]);
            }
        }

    }
    public function update(int $id, WorkSampleImageDTO $WorkSampleImageDTO, int $WorkSampleId)
    {
        $WorkSampleimage = $this->model::findOrfail($id);
        if ($WorkSampleImageDTO->image != null) {
            File::delete('assets/uploads/work-sample/' . $WorkSampleimage->image);
            $pathMain = "assets/uploads/work-sample/";
            $extension = $WorkSampleImageDTO->image->getClientOriginalExtension();
            $ext = ['jpg', 'jpeg', 'png', 'xls', 'mp3', 'ogg'];
            if (true) {
                $fileName = md5(microtime()) . ".$extension";
                $WorkSampleImageDTO->image->move($pathMain, $fileName);
                $imageName = $fileName;
            } else {
                return Redirect::back()->with('error', 'فایل ارسالی صحیح نیست');
            }
        } else {
            $imageName = $WorkSampleimage->image;
        }
        $WorkSampleimage->update([
            'image' => $imageName,
        ]);

    }
    public function destroy(int $id)
    {
        $WorkSample = $this->model::find($id);
        $this->model::destroy($id);
    }
}
