@php
    $theme_provider = $theme_provider ?? app(\App\Modules\General\Helper\ThemeProvider::class);
    $showPaymentStatus = $showPaymentStatus ?? false;
    $addressData = json_decode(@$order->address, true);
    $logoUrl = $theme_provider->getValue() == 'theme1'
        ? (@$settings['footer_logo'] ?? null)
        : (@$settings['logo'] ?? null);
    $itemCount = count($order->allItems ?? []);
@endphp

<div class="factor-doc">
    <div class="factor-doc__toolbar">
        <p class="factor-doc__toolbar-label">پیش‌نمایش فاکتور — آماده چاپ</p>
        <button type="button" class="factor-doc__print print-button" id="print-button">
            <i class="bi bi-printer"></i>
            چاپ فاکتور
        </button>
    </div>

    <article class="factor-doc__sheet" id="factor">
        <header class="factor-doc__hero">
            <div class="factor-doc__brand">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ @$settings['siteName_fa'] }}" class="factor-doc__logo" width="72" height="72">
                @endif
                <div>
                    <span class="factor-doc__eyebrow">{{ @$settings['siteName_fa'] }}</span>
                    <h1 class="factor-doc__title">صورتحساب فروش کالا</h1>
                </div>
            </div>

            <div class="factor-doc__meta">
                <div class="factor-doc__meta-item">
                    <span class="factor-doc__meta-label">تاریخ</span>
                    <span class="factor-doc__meta-value font-num-r">{{ @$order->date ?: '—' }}</span>
                </div>
                <div class="factor-doc__meta-item">
                    <span class="factor-doc__meta-label">ساعت</span>
                    <span class="factor-doc__meta-value font-num-r">{{ @$order->time ?: '—' }}</span>
                </div>
                <div class="factor-doc__meta-item">
                    <span class="factor-doc__meta-label">شماره فاکتور</span>
                    <span class="factor-doc__meta-value font-num-r">#{{ @$order->id }}</span>
                </div>
                <div class="factor-doc__meta-item">
                    <span class="factor-doc__meta-label">روش ارسال</span>
                    <span class="factor-doc__meta-value">{{ optional($order->shipping_method)->title ?? '—' }}</span>
                </div>
                @if($showPaymentStatus && @$order->status)
                    <div class="factor-doc__meta-item" style="grid-column: 1 / -1;">
                        <span class="factor-doc__meta-label">وضعیت پرداخت</span>
                        <span class="factor-doc__meta-value">{{ @$order->status['title'] }}</span>
                    </div>
                @endif
            </div>
        </header>

        <div class="factor-doc__parties">
            <section class="factor-doc__party">
                <div class="factor-doc__party-head">
                    <span class="factor-doc__party-icon"><i class="bi bi-shop"></i></span>
                    <h2 class="factor-doc__party-title">اطلاعات فروشنده</h2>
                </div>
                <div class="factor-doc__party-row">
                    <span class="factor-doc__party-label">نام فروشگاه</span>
                    <span class="factor-doc__party-value">{{ @$settings['siteName_fa'] ?: '—' }}</span>
                </div>
                <div class="factor-doc__party-row">
                    <span class="factor-doc__party-label">تلفن فروشگاه</span>
                    <span class="factor-doc__party-value font-num-r">{{ @$settings['main_phone_number'] ?: '—' }}</span>
                </div>
            </section>

            <section class="factor-doc__party">
                <div class="factor-doc__party-head">
                    <span class="factor-doc__party-icon"><i class="bi bi-person"></i></span>
                    <h2 class="factor-doc__party-title">اطلاعات گیرنده</h2>
                </div>
                <div class="factor-doc__party-row">
                    <span class="factor-doc__party-label">نام گیرنده</span>
                    <span class="factor-doc__party-value">{{ @$order->receiptor_full_name ?: '—' }}</span>
                </div>
                <div class="factor-doc__party-row">
                    <span class="factor-doc__party-label">تلفن گیرنده</span>
                    <span class="factor-doc__party-value font-num-r">{{ $addressData['receiptor_mobile'] ?? 'آدرس نادرست' }}</span>
                </div>
                <div class="factor-doc__party-row">
                    <span class="factor-doc__party-label">نشانی گیرنده</span>
                    <span class="factor-doc__party-value">
                        @if($addressData)
                            {{ trim(($addressData['state'] ?? '') . ' ' . ($addressData['city'] ?? '') . ' ' . ($addressData['address'] ?? '')) }}
                        @else
                            آدرس نادرست
                        @endif
                    </span>
                </div>
                <div class="factor-doc__party-row">
                    <span class="factor-doc__party-label">کدپستی</span>
                    <span class="factor-doc__party-value font-num-r">{{ $addressData['postal_code'] ?? 'آدرس نادرست' }}</span>
                </div>
            </section>
        </div>

        <div class="factor-doc__products">
            <div class="factor-doc__products-head">
                <h2 class="factor-doc__products-title">
                    <i class="bi bi-receipt"></i>
                    اقلام فاکتور
                </h2>
                <span class="factor-doc__products-count font-num-r">{{ $itemCount }} قلم</span>
            </div>

            <div class="factor-doc__table-wrap">
                <table class="factor-doc__table">
                    <thead>
                        <tr>
                            <th>ردیف</th>
                            <th class="text-start">محصول</th>
                            <th style="width: 70px;">تصویر</th>
                            <th style="width: 80px;">تعداد</th>
                            <th style="width: 120px;">قیمت واحد</th>
                            <th style="width: 120px;">قیمت کل</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->allItems as $key => $row)
                            @php
                                $imageUrl = @$row->product_variant_id
                                    ? (\App\Modules\Product\Services\VariantService::getProductImagesSizeSeperated(@$row->product_variant->images)[0]['image_medium'] ?? @$row->product->getImage())
                                    : @$row->product->getImage();
                                $unitPrice = intval($row->discounted_price) ?: intval($row->price);
                                $lineTotal = $row->discounted_price
                                    ? intval($row->discounted_price) * $row->quantity
                                    : intval($row->price) * $row->quantity;
                            @endphp
                            <tr>
                                <td><span class="factor-doc__index font-num-r">{{ $key + 1 }}</span></td>
                                <td class="text-start">
                                    <span class="factor-doc__product-name">{{ @$row->product->title }}</span>
                                    <div class="factor-doc__specs">
                                        @foreach(@$row->product_variant->specifications ?? [] as $specification)
                                            <span class="factor-doc__spec">
                                                @if(@$specification->parent->is_color)
                                                    <i class="factor-doc__color" style="background: {{ @$specification->color_code ?? '#ffffff' }}"></i>
                                                @endif
                                                {{ @$specification->parent->title }}: {{ @$specification->title }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <img src="{{ $imageUrl }}" alt="" class="factor-doc__thumb" width="48" height="48">
                                </td>
                                <td>
                                    <span class="font-num-r fw-bold">{{ @$row->quantity }}</span>
                                    @if(@$row->old_quantity != 0 && @$row->old_quantity != @$row->quantity)
                                        <del class="factor-doc__qty-old font-num-r">{{ @$row->old_quantity }}</del>
                                    @endif
                                </td>
                                <td>
                                    <span class="font-num-r">{{ number_format($unitPrice) }}</span>
                                    <span class="d-block small text-muted">تومان</span>
                                    @if(intval($row->discounted_price) != 0 && $row->discounted_price != $row->price)
                                        <del class="factor-doc__old-price font-num-r">{{ number_format(intval($row->price)) }}</del>
                                    @endif
                                </td>
                                <td>
                                    <strong class="font-num-r">{{ number_format($lineTotal) }}</strong>
                                    <span class="d-block small text-muted">تومان</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="factor-doc__summary">
                <div class="factor-doc__summary-row">
                    <span>جمع کل محصولات</span>
                    <span class="font-num-r">{{ number_format(intval($order->total_price ?? 0)) }} تومان</span>
                </div>
                <div class="factor-doc__summary-row">
                    <span>هزینه ارسال</span>
                    <span class="font-num-r">
                        {{ $order->shipping_name }}
                        @if(@$order->freight_balance_name['title'])
                            <small class="text-muted">({{ $order->freight_balance_name['title'] }})</small>
                        @endif
                    </span>
                </div>
                <div class="factor-doc__summary-row">
                    <span>تخفیف</span>
                    <span class="font-num-r">
                        @if($order->discount_id)
                            ({{ number_format(intval($order->discount_price ?? 0)) }} تومان)
                        @else
                            ندارد
                        @endif
                    </span>
                </div>
                <div class="factor-doc__summary-row">
                    <span>مالیات بر ارزش افزوده</span>
                    <span class="font-num-r">
                        @if(intval($order->current_tax) != 0)
                            {{ number_format(intval($order->tax_price ?? 0)) }} تومان
                            <small class="text-muted">({{ $order->current_tax }}٪)</small>
                        @else
                            ندارد
                        @endif
                    </span>
                </div>
                <div class="factor-doc__summary-row factor-doc__summary-row--total">
                    <span>مبلغ پرداختی</span>
                    <strong class="font-num-r">{{ number_format(intval($order->payment_price ?? 0)) }} تومان</strong>
                </div>
            </div>
        </div>

        <footer class="factor-doc__footer">
            این صورتحساب به‌صورت الکترونیکی صادر شده و فاقد مهر فیزیکی است.
        </footer>
    </article>
</div>
