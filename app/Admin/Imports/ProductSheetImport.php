<?php

namespace App\Admin\Imports;

use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductSheetImport implements ToArray, WithHeadingRow
{
    public function array(array $array): array
    {
        return $array;
    }
}
