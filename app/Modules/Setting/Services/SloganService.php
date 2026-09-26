<?php
namespace App\Modules\Setting\Services;

use App\Modules\General\Helper\FileManager;
use App\Modules\Setting\DTO\SloganDTO;
use App\Modules\Setting\Entities\Slogan;


class SloganService
{
    public function create(SloganDTO $sloganDTO)
    {
        $image = $sloganDTO->getIcon() ? FileManager::upload($sloganDTO->getIcon(),"setting") : null;
        Slogan::create([
            'value' => $sloganDTO->getValue(),
            'active' => $sloganDTO->getActive(),
            'icon' => $image,
        ]);

    }
    public function update(int $id, SloganDTO $sloganDTO)
    {
        $gallery = Slogan::findOrfail($id);
        $image = $sloganDTO->getIcon() ? FileManager::upload($sloganDTO->getIcon(),"setting") : $gallery->getRawOriginal('icon');
        $gallery->update([
            'value' => $sloganDTO->getValue(),
            'active' => $sloganDTO->getActive(),
            'icon' => $image,
        ]);
    }
    public function destroy(int $id)
    {
        Slogan::destroy($id);
    }

    public static function findAll($query =[], $limit = null)
    {
        $data = Slogan::query();
        if (isset($query['first_page'])) {
            $data->firstPage();
        }
        if (isset($query['active'])) {
            $data->where('active','1');
        }
        return $data->take($limit)->get();
    }
}
