<?php
namespace App\Modules\Setting\Services;

use App\Modules\Setting\DTO\SocialDTO;
use App\Modules\Setting\Entities\Social;

class SocialService
{
    public static function findAll()
    {
        return Social::orderBy('id','DESC')->get();
    }
    public static function myIcon()
    {
        return Social::orderBy('id','DESC')->where('is_app_icon',1)->first();
    }
    public function create(SocialDTO $socialDTO)
    {
        if ($socialDTO->getIsAppIcon() == 1){
           Social::where('is_app_icon',1)->update(['is_app_icon'=>0]);
        }
        return Social::create([
            'icon' => $socialDTO->getIcon(),
            'link' => $socialDTO->getLink(),
            'is_app_icon' => $socialDTO->getIsAppIcon(),
        ]);
    }
    public function update(int $id, SocialDTO $socialDTO)
    {
        $social = Social::findOrfail($id);
        if ($socialDTO->getIsAppIcon() == 1){
            Social::where('is_app_icon',1)->where('id','<>',$id)->update(['is_app_icon'=>0]);
        }
        $social->update([
            'icon' => $socialDTO->getIcon(),
            'link' => $socialDTO->getLink(),
            'is_app_icon' => $socialDTO->getIsAppIcon(),
        ]);
    }
    public function destroy(int $id)
    {
        Social::destroy($id);
    }

    public function getInstagram()
    {
        return Social::where('icon','instagram')->first();
    }
}
