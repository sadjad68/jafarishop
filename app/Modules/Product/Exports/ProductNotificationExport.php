<?php

namespace App\Modules\Product\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductNotificationExport implements FromCollection, WithHeadings, WithMapping
{
    protected $items;

    public function __construct($items)
    {
        $this->items = $items;
    }

    public function collection()
    {
        return $this->items;
    }

    public function headings(): array
    {
        return [
            'شناسه',
            'نام کاربر',
            'شماره تماس',
            'نام محصول',
            'متغیر',
            'وضعیت ارسال',
            'تاریخ درخواست',
        ];
    }

    public function map($item): array
    {
        return [
            $item->id,
            $item->user->full_name ?? $item->user->name ?? 'کاربر ناشناس',
            $item->user->mobile ?? '-',
            $item->product->title ?? 'محصول حذف شده',
            $item->variant->variant_title ?? ($item->product_variant_id ? 'یافت نشد' : 'بدون متغیر'),
            $item->is_sent ? 'ارسال شده' : 'در انتظار',
            $item->getJalaliCreateAtWithHour(),
        ];
    }
}
