<?php

namespace App\Modules\Banner\Services;

use App\Modules\General\Helper\FileUploader;
use App\Modules\Banner\DTO\BannerDTO;
use App\Modules\Banner\Entities\Banner;
use App\Modules\General\Helper\ThemeProvider;

class BannerService
{
    public function create(BannerDTO $bannerDTO)
    {
        $image = null;
        if ($bannerDTO->getImage()) {
            $uploader = new FileUploader($bannerDTO->getImage(), "uploads/banner");
            $uploader->setExtensions(["jpeg", "webp", "png", "jpg"]);
            $uploader->setSizes(["big" => [app(ThemeProvider::class)->getSliderSizes()['desktop']['width'], app(ThemeProvider::class)->getSliderSizes()['desktop']['height']]]);
            $image = $uploader->upload();
        }
        $image_mobile = null;
        if ($bannerDTO->getImageMobile()) {
            $uploader = new FileUploader($bannerDTO->getImageMobile(), "uploads/banner");
            $uploader->setExtensions(["jpeg", "webp", "png", "jpg"]);
            $uploader->setSizes(["big" => [app(ThemeProvider::class)->getSliderSizes()['mobile']['width'], app(ThemeProvider::class)->getSliderSizes()['mobile']['height']]]);
            $image_mobile = $uploader->upload();
        }
        Banner::create([
            'image' => $image,
            'image_mobile' => $image_mobile,
            'title' => $bannerDTO->getTitle(),
            'link' => $bannerDTO->getLink(),
            'show_in_first_page' => $bannerDTO->getShowInFirstPage(),
        ]);
    }

    public function update(int $id, BannerDTO $bannerDTO)
    {
        $banner = Banner::findOrfail($id);
        $image = $banner->getRawOriginal('image');
        if ($bannerDTO->getImage()) {
            $uploader = new FileUploader($bannerDTO->getImage(), "uploads/banner");
            $uploader->setExtensions(["jpeg", "webp", "png", "jpg"]);
            $uploader->setSizes(["big" => [app(ThemeProvider::class)->getSliderSizes()['desktop']['width'], app(ThemeProvider::class)->getSliderSizes()['desktop']['height']]]);
            $image = $uploader->upload();
        }
        $image_mobile = $banner->getRawOriginal('image_mobile');
        if ($bannerDTO->getImageMobile()) {
            $uploader = new FileUploader($bannerDTO->getImageMobile(), "uploads/banner");
            $uploader->setExtensions(["jpeg", "webp", "png", "jpg"]);
            $uploader->setSizes(["big" => [app(ThemeProvider::class)->getSliderSizes()['mobile']['width'], app(ThemeProvider::class)->getSliderSizes()['mobile']['height']]]);
            $image_mobile = $uploader->upload();
        }
        $banner->update([
            'title' => $bannerDTO->getTitle(),
            'image_mobile' => $image_mobile,
            'image' => $image,
            'link' => $bannerDTO->getLink(),
            'show_in_first_page' => $bannerDTO->getShowInFirstPage(),
        ]);
    }

    public function destroy(int $id)
    {
        Banner::destroy($id);
    }

    public static function findAll($query, $limit = 10)
    {
        $data = Banner::query();
        if (isset($query['first_page'])) {
            $data->firstPage();
        }
        return $data->orderby('sort', 'ASC')->take($limit)->get();
    }

}
