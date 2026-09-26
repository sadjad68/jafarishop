<?php

namespace App\Modules\Seo\Services;

use App\Modules\Seo\DTO\RedirectDTO;
use \App\Modules\Seo\Entities\Redirect as RedirectModel;

class RedirectService
{
    public function create(RedirectDTO $redirectDTO)
    {
        RedirectModel::create([
            'old_address' => $redirectDTO->getOldAddress(),
            'new_address' => $redirectDTO->getNewAddress(),
            'type' => $redirectDTO->getType(),
        ]);
    }


    public function destroy(int $id)
    {
        RedirectModel::destroy($id);
    }

    public static function findAll()
    {
        return RedirectModel::orderBy('id', 'DESC')->get();
    }
    public static function findNotRedirected($address)
    {
        return RedirectModel::where('old_address', $address)->exists();
    }
}
