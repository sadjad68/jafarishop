<?php

namespace App\Admin\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductImportSampleExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'name',
            'url',
            'category',
            'brand',
            'price',
            'discounted_price',
            'stock',
        ];
    }

    public function array(): array
    {
        return [
            [
                'محصول نمونه',
                'product-sample-url',
                'نام دسته‌بندی دقیق از سایت',
                'نام برند دقیق از سایت',
                '100000',
                '90000',
                '5',
            ],
        ];
    }
}
