<?php

namespace App\Modules\Order\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Modules\Order\Entities\Order;
use App\Modules\Order\Filters\OrderFilter;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class OrderExport implements FromQuery, WithHeadings, WithMapping, WithChunkReading
{
    function __construct($request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $request = $this->request;
        $query = Order::query();
        if ($request->has(['filter'])) {
            $filters = [
                'full_name' => $request->input('full_name'),
                'shipping_method_id' => $request->input('shipping_method_id'),
                'shipping_status_id' => $request->input('shipping_status_id'),
                'order_status' => $request->input('order_status'),
                'user_id' => $request->input('user_id'),
                'id' => $request->input('id'),
                'mobile' => $request->input('mobile'),
                'from_date' => $request->input('from_date'),
                'to_date' => $request->input('to_date'),
            ];
            $query = app(OrderFilter::class)->apply($query, $filters);
        }
        return $query->orderBy('id', 'DESC');
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('T:T') // ستون محصولات
        ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_TOP);
    }
    public function map($order): array
    {
        $products = $order->allItems->map(function ($item) {
            return optional($item->product)->title;
        })->filter()->implode("\r\n");
        return [
            $order->id,
            optional($order->user)->full_name,
            optional($order->user)->mobile,
            $order->receiptor_full_name,
            json_decode(@$order->address,true)['receiptor_mobile'],
            optional($order->shipping_method)->title,
            optional($order->shipping_status)->title,
            $order->status['title'] ?? '',
            $order->date,
            json_decode(@$order->address,true)['postal_code'],
            json_decode(@$order->address,true)['state'],
            json_decode(@$order->address,true)['city'],
            json_decode(@$order->address,true)['address'],
            @$order->bank->title ?? '',
            !empty($order->bank_tracking_code) ? $order->bank_tracking_code : '',
            number_format($order->total_price),
            number_format($order->payment_price),
            number_format($order->discount_price),
            optional($order->discount)->title,
            $products,
            count($order->allItems)
        ];
    }
    public function headings(): array
    {
        return [
            'شماره سفارش',
            'نام و نام خانوادگی کاربر',
            'شماره تماس کاربر',
            'نام و نام خانوادگی گیرنده',
            'شماره تماس گیرنده',
            'روش ارسال',
            'وضعیت سفارش',
            'وضعیت پرداخت',
            'تاریخ',
            'کد پستی',
            'استان',
            'شهر',
            'آدرس',
            'نوع درگاه',
            'کدپیگیری بانک',
            'مبلغ کل',
            'مبلغ پرداختی',
            'مبلغ تخفیف',
            'کد تخفیف',
            'محصولات',
            'تعداد اقلام',
        ];
    }
    public function chunkSize(): int
    {
        return 100;
    }
}
