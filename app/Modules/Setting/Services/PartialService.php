<?php
namespace App\Modules\Setting\Services;

use App\Modules\Setting\DTO\PartialDTO;
use App\Modules\Setting\Entities\SettingPartial;


class PartialService
{
    public function create(PartialDTO $partialDTO)
    {
        SettingPartial::create([
            'title' => $partialDTO->getTitle(),
            'parent_id' => $partialDTO->getParentId(),
            'show' => $partialDTO->getShow(),
        ]);

    }
    public function update(int $id, PartialDTO $partialDTO)
    {
        $partial = SettingPartial::findOrfail($id);
        $partial->update([
            'title' => $partialDTO->getTitle(),
            'parent_id' => $partialDTO->getParentId(),
            'show' => $partialDTO->getShow()
        ]);
    }
    public function destroy(int $id)
    {
           SettingPartial::destroy($id);
    }

}
