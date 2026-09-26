<?php

namespace App\Modules\Order\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Modules\General\Helper\DateHelper;
use App\Modules\Order\Entities\Basket;
use App\Modules\Order\Entities\Order;
use App\Modules\Order\Filters\OrderFilter;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class BasketExport implements FromQuery, WithHeadings, WithMapping, WithChunkReading
{
    function __construct($request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $request = $this->request;
        $query = Basket::query()->with(['user', 'items']);

        // فیلتر کاربر
        if ($request->boolean('has_user')) {
            $query->whereNotNull("user_id");
            if ($request->filled('user_ids')) {
                $userIds = explode(',', $request->user_ids);
                $query->whereIn('user_id', $userIds);
            }
        }

        // فیلتر موبایل
        if ($request->filled('mobile')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('mobile', 'like', "%{$request->mobile}%");
            });
        }

        // فیلتر محصول
        if ($request->filled('product_ids')) {
            $productIds = explode(',', $request->product_ids);
            $query->whereHas('items', function ($q) use ($productIds) {
                $q->whereIn('product_id', $productIds);
            });
        }

        // فیلتر آدرس
        if ($request->boolean('has_address')) {
            $query->whereNotNull("address_id");
            $query->whereHas('address', function ($q) use ($request) {
                if ($request->filled('state_id')) $q->where('state_id', $request->state_id);
                if ($request->filled('city_id')) $q->where('city_id', $request->city_id);
                if ($request->filled('postal_code')) $q->where('postal_code', 'like', "%{$request->postal_code}%");
            });
        }

        // اصلاح فیلتر تاریخ (مطابق منطق OrderFilter)
        if ($request->filled('from_date') || $request->filled('to_date')) {
            $from_date = $request->filled('from_date') ? DateHelper::convertDate($request->from_date) : null;
            $to_date = $request->filled('to_date') ? DateHelper::convertDate($request->to_date) : null;

            if (!empty($from_date) && !empty($to_date)) {
                $query->whereBetween('created_at', [
                    $from_date . ' 00:00:00',
                    $to_date . ' 23:59:59',
                ]);
            } elseif (!empty($from_date)) {
                $query->where('created_at', '>=', $from_date . ' 00:00:00');
            } elseif (!empty($to_date)) {
                $query->where('created_at', '<=', $to_date . ' 23:59:59');
            }
        }

        return $query->orderBy('id', 'DESC');
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('T:T')
        ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_TOP);
    }
    public function map($basket): array
    {
        $products = $basket->items->map(function ($item) {
            return optional($item->product)->title;
        })->filter()->implode("\r\n");
        return [
            optional($basket->user)->full_name,
            optional($basket->user)->mobile,
            $products,
            jdate('H:i - Y/m/d',$basket->updated_at->timestamp)
        ];
    }
    public function headings(): array
    {
        return [
            'نام و نام خانوادگی کاربر',
            'شماره تماس کاربر',
            'محصولات',
            'تاریخ آخرین تغییر'
        ];
    }
    public function chunkSize(): int
    {
        return 100;
    }
}
