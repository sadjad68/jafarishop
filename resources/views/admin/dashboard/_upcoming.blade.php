@php
    $upcomingUpdates = [
        [
            'title' => 'علاقه‌مندی‌ها در پنل کاربری',
            'text' => 'مشتری می‌تواند محصولات را ذخیره کند و بعداً از حساب کاربری به سبد خرید اضافه کند.',
        ],
        [
            'title' => 'مقایسه محصولات',
            'text' => 'امکان انتخاب چند محصول و مقایسه مشخصات، قیمت و موجودی در یک صفحه.',
        ],
        [
            'title' => 'دوره‌های آموزشی',
            'text' => 'فروش دوره، دسته‌بندی و جلسات آموزشی در سایت (ماژول LMS).',
        ],
        [
            'title' => 'گزارش‌های پیشرفته‌تر فروش',
            'text' => 'خروجی و فیلترهای بیشتر روی نمودار داشبورد، به‌تفکیک محصول و روش پرداخت.',
        ],
    ];
@endphp
@if(count($upcomingUpdates) > 0)
                <div class="row w-100 m-0 mt-4 px-0">
                    <div class="admin-section-title">
                        <span class="admin-section-ico"><i class="bi bi-clock-history"></i></span>
                        <span class="admin-section-label">آپدیت‌های بعدی</span>
                        <hr>
                    </div>
                    <div class="col-12 p-md-2 p-1">
                        <div class="admin-features-card admin-upcoming-card">
                            <p class="admin-upcoming-intro">این قابلیت‌ها در حال آماده‌سازی‌اند و به‌زودی روی سایت فعال می‌شوند.</p>
                            <ul class="admin-features-list admin-upcoming-list">
                                @foreach($upcomingUpdates as $update)
                                    <li>
                                        <i class="bi bi-hourglass-split" aria-hidden="true"></i>
                                        <span>
                                            <span class="admin-upcoming-title-row">
                                                <strong>{{ $update['title'] }}</strong>
                                                <em class="admin-upcoming-badge">به‌زودی</em>
                                            </span>
                                            {{ $update['text'] }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
@endif
