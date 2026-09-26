@extends('pages.panel.master')
@section('order','active')
@section('logo')
<img src="{{$settings['footer_logo']}}" width="120" alt="{{$settings['siteName_fa']}}" title="{{$settings['siteName_fa']}}" class="logo-menu">
@endsection
@section('content')
@php
    $address = json_decode(@$order->address, true);
    $orderActions = '<a href="#factor" class="sk-cta sk-cta--ghost btn-sm py-2 px-3 font-th">مشاهده فاکتور</a>'
        . '<a href="' . route('panel.order-factor', ['id' => $order->id]) . '" target="_blank" class="sk-cta sk-cta--ghost btn-sm py-2 px-3 font-th"><i class="bi bi-printer"></i> چاپ</a>';
    if ($order->order_status == 'deposit_paid' || ($order->order_status == 'paying' && $isCardToCard)) {
        $orderActions .= '<a href="' . route('basket.order-images', ['id' => $order->id]) . '" target="_blank" class="sk-cta btn-sm py-2 px-3 font-th"><i class="bi bi-cloud-upload"></i> آپلود فیش</a>';
    }
@endphp

<div class="order-page">
    <div class="order-page__back mb-2">
        <a href="{{ route('panel.orders') }}" class="order-page__back-link">
            <i class="bi bi-arrow-right"></i>
            بازگشت به سفارشات
        </a>
    </div>

    @include('pages.panel.order._partials.summary-hero', compact('order', 'shippingStatusColor', 'paymentStatusColor', 'isAwaitingPaymentVerification'))

    <div class="panel-card panel-card--flush mt-3 mb-3">
        <div class="panel-card__head panel-card__head--split px-3 pt-3 pb-0 border-0">
            <div>
                <h2 class="panel-card__title" style="font-size:1rem;">
                    <span class="panel-card__title-icon"><i class="bi bi-sliders"></i></span>
                    عملیات
                </h2>
            </div>
            <div class="panel-card__actions sk-cta-row mb-3">{!! $orderActions !!}</div>
        </div>
    </div>

    <div class="panel-info-grid">
        @php
            $orderInfoRows = [
                ['icon' => 'bi-calendar3', 'label' => 'تاریخ سفارش', 'value' => e(@$order->date ?? '—')],
                ['icon' => 'bi-wallet2', 'label' => 'نحوه پرداخت', 'value' => e($order->bank->title) . (intval(@$order->gateway_tariff) > 0 ? ' <small class="text-muted">(تعرفه ' . intval($order->gateway_tariff) . '٪)</small>' : '')],
                ['icon' => 'bi-truck', 'label' => 'نحوه ارسال', 'value' => e(@$order->shipping_method->title ?? '—')],
                ['icon' => 'bi-envelope-check', 'label' => 'کد پیگیری پستی', 'value' => e($order->post_code ?? '—')],
            ];
            if ($order->bijak_image_asset) {
                $orderInfoRows[] = [
                    'icon' => 'bi-file-earmark-image',
                    'label' => 'بیجک',
                    'value' => '<button type="button" class="sk-cta sk-cta--ghost btn-sm py-1 px-3" data-bs-toggle="modal" data-bs-target="#bijakLightbox"><img src="' . e($order->bijak_image_asset) . '" width="36" height="36" class="rounded-2 object-fit-cover me-1" alt=""> مشاهده</button>',
                ];
            }
        @endphp
        @include('pages.panel.order._partials.info-section', [
            'icon' => 'bi-receipt-cutoff',
            'title' => 'اطلاعات سفارش',
            'rows' => $orderInfoRows,
        ])

        @php
            $receiptValue = '';
            if ($isCardToCard) {
                if ($receipt) {
                    $receiptValue = '<div class="d-flex align-items-center gap-2 flex-wrap justify-content-end">';
                    if ($receiptStatus) {
                        $receiptValue .= '<span class="panel-badge" style="--badge-color:' . e($receiptStatus['color']) . '">' . e($receiptStatus['title']) . '</span>';
                    }
                    $receiptValue .= '<button type="button" class="sk-cta sk-cta--ghost btn-sm py-1 px-3" data-bs-toggle="modal" data-bs-target="#receiptLightbox">';
                    $receiptValue .= $receiptIsPdf ? '<i class="bi bi-file-earmark-pdf"></i> مشاهده فیش' : '<img src="' . e($receiptUrl) . '" width="36" height="36" class="rounded-2 object-fit-cover me-1"> مشاهده';
                    $receiptValue .= '</button></div>';
                } else {
                    $receiptValue = '<span class="text-muted small">ثبت نشده</span>';
                }
            } else {
                $tracking = @$order->bank_tracking_code ?: (json_decode(@$order->transaction_info, true)['post']['transactionId'] ?? '');
                $receiptValue = e($tracking ?: '—');
            }

            $customerRows = [
                ['icon' => 'bi-person', 'label' => 'نام', 'value' => e($order->user->full_name)],
                ['icon' => 'bi-telephone', 'label' => 'تلفن', 'value' => e($order->user->mobile)],
                ['icon' => 'bi-shield-check', 'label' => 'وضعیت سفارش', 'value' => '<span class="panel-badge" style="--badge-color:' . e($shippingStatusColor) . '">' . e(@$order->shipping_status->title) . '</span>'],
            ];
            if (@$order->status) {
                $paymentBadgeHtml = $isAwaitingPaymentVerification
                    ? '<span class="panel-badge panel-badge--amber">' . e($order->status['title']) . '</span>'
                    : '<span class="panel-badge" style="--badge-color:' . e($paymentStatusColor) . '">' . e($order->status['title']) . '</span>';
                $customerRows[] = ['icon' => 'bi-credit-card', 'label' => 'وضعیت پرداخت', 'value' => $paymentBadgeHtml];
            }
            $customerRows[] = ['icon' => 'bi-bank', 'label' => $isCardToCard ? 'فیش واریزی' : 'کد پیگیری درگاه', 'value' => $receiptValue];
        @endphp

        @include('pages.panel.order._partials.info-section', [
            'icon' => 'bi-person-badge',
            'title' => 'اطلاعات مشتری',
            'rows' => $customerRows,
        ])
    </div>

    @include('pages.panel.order._partials.info-section', [
        'icon' => 'bi-geo-alt',
        'title' => 'آدرس تحویل',
        'fullWidth' => true,
        'layout' => 'address',
        'rows' => [
            ['icon' => 'bi-pin-map', 'label' => 'شهر و استان', 'value' => e($address ? ($address['state'] . ' - ' . $address['city']) : 'آدرس نادرست')],
            ['icon' => 'bi-mailbox', 'label' => 'کدپستی', 'value' => e($address['postal_code'] ?? '—')],
            ['icon' => 'bi-person', 'label' => 'نام گیرنده', 'value' => e(@$order->receiptor_full_name ?? '—')],
            ['icon' => 'bi-telephone', 'label' => 'تلفن گیرنده', 'value' => e($address['receiptor_mobile'] ?? '—')],
            ['icon' => 'bi-map', 'label' => 'آدرس کامل', 'value' => e($address['address'] ?? '—'), 'wide' => true],
        ],
    ])

    @if(@$order->user_description)
        <div class="panel-info-section mt-3">
            <div class="panel-info-section__head">
                <span class="panel-info-section__icon"><i class="bi bi-chat-left-text"></i></span>
                <h3 class="panel-info-section__title">توضیحات شما</h3>
            </div>
            <div class="panel-info-section__body panel-info-section__body--note">
                {!! $order->user_description !!}
            </div>
        </div>
    @endif

    @include('pages.panel.order._partials.invoice', ['order' => $order])

    @if(!$isCardToCard && count($order->images) > 0)
        <div class="panel-receipts mt-3">
            <div class="panel-receipts__head">
                <i class="bi bi-images"></i>
                <span>فیش‌های واریزی</span>
            </div>
            <div class="panel-receipts__grid">
                @foreach($order->images as $image)
                    @if(strtolower(pathinfo($image->file, PATHINFO_EXTENSION)) === 'pdf')
                        <a href="{{ asset('uploads/order/'.$order->id.'/'.$image->file) }}" target="_blank" class="panel-receipts__pdf">
                            <i class="bi bi-file-earmark-pdf"></i>
                            PDF
                        </a>
                    @else
                        <a href="{{ asset('uploads/order/'.$order->id.'/'.$image->file) }}" target="_blank" class="panel-receipts__img">
                            <img src="{{ asset('uploads/order/'.$order->id.'/'.$image->file) }}" alt="فیش">
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    @endif
</div>

@if($order->bijak_image_asset)
<div class="modal fade" id="bijakLightbox" tabindex="-1" aria-labelledby="bijakLightboxLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0 bg-transparent">
            <div class="modal-header border-0 bg-white rounded-top-3">
                <h5 class="modal-title" id="bijakLightboxLabel">تصویر بیجک سفارش #{{ $order->id }}</h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="بستن"></button>
            </div>
            <div class="modal-body bg-dark text-center p-2 rounded-bottom-3">
                <img src="{{ $order->bijak_image_asset }}" alt="تصویر بیجک" class="img-fluid rounded-2" style="max-height:75vh;">
            </div>
        </div>
    </div>
</div>
@endif

@if($isCardToCard && $receipt)
<div class="modal fade" id="receiptLightbox" tabindex="-1" aria-labelledby="receiptLightboxLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0 bg-transparent">
            <div class="modal-header border-0 bg-white rounded-top-3">
                <h5 class="modal-title" id="receiptLightboxLabel">فیش واریزی سفارش #{{ $order->id }}</h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="بستن"></button>
            </div>
            <div class="modal-body bg-dark text-center p-2 rounded-bottom-3">
                @if($receiptIsPdf)
                    <iframe src="{{ $receiptUrl }}" title="فیش واریزی" class="w-100 bg-white rounded-2" style="height:75vh;"></iframe>
                @else
                    <img src="{{ $receiptUrl }}" alt="فیش واریزی" class="img-fluid rounded-2" style="max-height:75vh;">
                @endif
            </div>
        </div>
    </div>
</div>
@endif
@endsection
