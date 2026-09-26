<?php
namespace App\Modules\Certification\Services;

use App\Modules\General\Helper\FileManager;
use App\Modules\General\Helper\FileUploader;
use App\Modules\Certification\DTO\CertificateDTO;
use App\Modules\Certification\Entities\Certificate;

class CertificateService
{

    public function create(CertificateDTO $certificateDTO)
    {
        $image = null;
        if ($certificateDTO->getImage()) {
            $uploader = new FileUploader($certificateDTO->getImage(), "uploads/certificate");
            $uploader->setExtensions(["jpeg", "webp", "png", "jpg"]);
            $uploader->setSizes(["big" => [700,700]]);
            $image = $uploader->upload();
        }
        Certificate::create([
            'title' => $certificateDTO->getTitle(),
            'show_in_first_page' => $certificateDTO->getShowInFirstPage(),
            'image' => $image,
        ]);

    }
    public function update(int $id, CertificateDTO $certificateDTO)
    {
        $gallery = Certificate::findOrfail($id);
        $image = $gallery->getRawOriginal('image');
        if ($certificateDTO->getImage()) {
            $uploader = new FileUploader($certificateDTO->getImage(), "uploads/certificate");
            $uploader->setExtensions(["jpeg", "webp", "png", "jpg"]);
            $uploader->setSizes(["big" => [700,700]]);
            $image = $uploader->upload();
        }
        $gallery->update([
            'title' => $certificateDTO->getTitle(),
            'show_in_first_page' => $certificateDTO->getShowInFirstPage(),
            'image' => $image,
        ]);
    }
    public function destroy(int $id)
    {
        Certificate::destroy($id);
    }
    //clientSide
    public static function findAll($query, $limit = null)
    {
        $data = Certificate::query();
        if (isset($query['first_page'])) {
            $data->firstPage();
        }
        if (!is_null($limit)) {
            $data->take($limit);
        }
        return $data->get();
    }
}
