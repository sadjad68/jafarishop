<?php

namespace App\Modules\Setting\Services;

use App\Modules\Setting\DTO\BranchDTO;
use App\Modules\Setting\Entities\Branch;

class BranchService
{
    public function create(BranchDTO $branchDTO)
    {
        if ($branchDTO->getMain() == 1) {
            Branch::mainBranch()->update(['main' => 0]);
        }
        Branch::create([
            'title' => $branchDTO->getTitle(),
            'address' => $branchDTO->getAddress(),
            'map' => $branchDTO->getMap(),
            'main' => $branchDTO->getMain(),
        ]);

         
    }

    public function update(int $id, BranchDTO $branchDTO)
    {
        $branch = Branch::findOrfail($id);
        if ($branchDTO->getMain() == 1) {
            Branch::mainBranch()->where('id', '<>', $id)->update(['main' => 0]);
        }
        $branch->update([
            'title' => $branchDTO->getTitle(),
            'address' => $branchDTO->getAddress(),
            'map' => $branchDTO->getMap(),
            'main' => $branchDTO->getMain(),
        ]);
    }

    public function destroy(int $id)
    {
        Branch::destroy($id);
    }

    public static function findAll()
    {
        return Branch::orderBy('id', 'DESC')->get();
    }
}
