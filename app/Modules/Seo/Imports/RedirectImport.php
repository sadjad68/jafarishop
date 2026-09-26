<?php

namespace App\Modules\Seo\Imports;


use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Modules\Seo\DTO\RedirectDTO;
use App\Modules\Seo\Entities\Redirect as RD;
use App\Modules\Seo\Services\RedirectService;

class RedirectImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $check = RD::orderBy('id', 'DESC')->where('old_address', '/' . trim(str_replace(url('/'), "", $row['old_address']), '/'))->first();
            if ($check) {
                $check->delete();
            }

            $old_address = trim(str_replace(url('/'), "", $row['old_address']), '/');
            $new_address = str_replace(url('/'), "", $row['new_address']);
            if (strlen($old_address) != 0) {
                if ($new_address != "/") {
                    $new_address = trim(str_replace(url('/'), "", $row['new_address']), '/');
                }
                RD::create([
                    'old_address' => $old_address,
                    'new_address' => $new_address,
                    'type' => @$row['type'] ? @$row['type'] : 301,
                ]);
            }

        }
    }
}

