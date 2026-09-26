<div class="col-lg-4 col-md-6 col-12 p-0">
    @include('pages.panel._partials.stat-card', [
        'tone' => 'brand',
        'icon' => 'bi-person-vcard',
        'title' => 'اطلاعات کاربری',
        'value' => \Illuminate\Support\Facades\Auth::user()->full_name,
        'label' => \Illuminate\Support\Facades\Auth::user()->mobile ?: '—',
        'editLink' => route('panel.profile'),
        'link' => route('panel.profile'),
        'linkLabel' => 'ویرایش پروفایل',
    ])
</div>
<div class="col-lg-4 col-md-6 col-12 p-0">
    @include('pages.panel._partials.stat-card', [
        'tone' => 'violet',
        'icon' => 'bi-handbag',
        'title' => 'سفارشات',
        'value' => count($user->orders),
        'label' => 'سفارش ثبت‌شده',
        'link' => route('panel.orders'),
        'linkLabel' => 'مشاهده همه',
    ])
</div>
<div class="col-lg-4 col-md-6 col-12 p-0">
    @include('pages.panel._partials.stat-card', [
        'tone' => 'mint',
        'icon' => 'bi-cart3',
        'title' => 'سبد خرید',
        'value' => @$user->basket ? count($user->basket->items) : 0,
        'label' => 'کالا در سبد',
        'link' => route('basket.cart'),
        'linkLabel' => 'رفتن به سبد',
    ])
</div>
