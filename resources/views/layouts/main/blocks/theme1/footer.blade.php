{{-- scroll to top --}}
<button type="button" id="scroll-to-top" style="display: none" class="btn btn-to-top">
    <i class="bi bi-arrow-up d-flex"></i>
</button>

@php
    $work_hours = is_array(@$settings['work_hours']) ? $settings['work_hours'] : [];
    $allNull = collect($work_hours)->every(function ($day) {
        return is_null($day['from'] ?? null) && is_null($day['to'] ?? null);
    });
    $footer_links = is_array(@$settings['footer_links']) ? $settings['footer_links'] : [];
    $phone_numbers = is_array(@$settings['phone_numbers']) ? $settings['phone_numbers'] : [];
    $footer_valids = is_array(@$settings['footer_valids']) ? $settings['footer_valids'] : [];
    $show_services = !empty($settings['service_in_footer']) && isset($footer_services) && count($footer_services) > 0;
    $show_categories = @$settings['category_in_footer'] == 1 && isset($footer_product_categories) && count($footer_product_categories) > 0;
    $show_namads = @$settings['enamad'] != null || count($footer_valids) > 0;
    $show_col_brand = $show_services || $show_namads;
    $has_branches = isset($branches) && count($branches) > 0;
    $site_name = trim((string) (@$settings['siteName_fa'] ?? ''));
    $footer_logo = $settings['footer_logo'] ?? ($settings['logo'] ?? null);
    $address = trim((string) (@$settings['address'] ?? ''));
    $email = trim((string) (@$settings['email'] ?? ''));
    $map_url = trim((string) (@$settings['map'] ?? ''));
    $show_socials = isset($socials) && count($socials) > 0;
    $show_intro = !empty($footer_logo) || !empty($settings['footer_about_title']) || !empty($settings['footer_about_text']);
    $show_contact = $address !== '' || $email !== '' || count($phone_numbers) > 0 || $has_branches;
    $social_names = [
        'whatsapp' => 'واتساپ',
        'telegram' => 'تلگرام',
        'bale' => 'بله',
        'eitaa' => 'ایتا',
        'instagram' => 'اینستاگرام',
        'linkedin' => 'لینکدین',
        'facebook' => 'فیسبوک',
        'youtube' => 'یوتیوب',
        'tiktok' => 'تیک‌تاک',
        'pinterest' => 'پینترست',
        'Soroush' => 'سروش',
        'rubika' => 'روبیکا',
        'aparat' => 'آپارات',
        'castbox' => 'کست‌باکس',
    ];
@endphp

<footer class="footer t1-footer">
    <div class="main-footer">
        <div class="container">
            @if ($show_intro || $show_socials)
                <div class="t1-footer__mast">
                    @if ($show_intro)
                        <div class="t1-footer__brand">
                            @if (!empty($footer_logo))
                                <div class="t1-footer__mark">
                                    <img src="{{ $footer_logo }}"
                                         class="t1-footer__logo"
                                         alt="{{ $site_name }}"
                                         title="{{ $site_name }}"
                                         width="150"
                                         height="85"
                                         loading="lazy">
                                </div>
                            @endif
                            <div id="t1-footer-about" class="t1-footer__collapse">
                                @if (!empty($settings['footer_about_title']))
                                    <p class="t1-footer__intro-title">
                                        {!! $settings['footer_about_title'] !!}
                                    </p>
                                @endif
                                @if (!empty($settings['footer_about_text']))
                                    <div id="t1-footer-about-text" class="t1-footer__intro-text">
                                        {!! $settings['footer_about_text'] !!}
                                    </div>
                                @endif
                            </div>
                            <button type="button"
                                    id="t1-footer-collapse-btn"
                                    class="t1-footer__more"
                                    aria-expanded="false"
                                    aria-controls="t1-footer-about"
                                    hidden>
                                <span id="t1-footer-collapse-label">مشاهده بیشتر</span>
                                <i class="bi bi-chevron-down t1-footer__more-icon" aria-hidden="true"></i>
                            </button>
                        </div>
                    @endif

                    @if ($show_socials)
                        <ul class="t1-footer__social">
                            @foreach ($socials as $social)
                                @php
                                    $social_label = $social_names[$social['icon']] ?? $social['icon'];
                                @endphp
                                <li>
                                    <a href="{!! $social['link'] !!}"
                                       class="t1-footer__social-link"
                                       target="_blank"
                                       rel="noopener noreferrer nofollow"
                                       aria-label="{{ $social_label }}">
                                        <img width="20"
                                             height="20"
                                             src="{{ asset('assets/site/images/socials/' . $social['icon'] . '.png') }}"
                                             alt="">
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif

            <div class="t1-footer__grid">
                @if ($show_col_brand)
                    <div class="t1-footer__col">
                        <p class="t1-footer__label">با {{ $site_name !== '' ? $site_name : 'ما' }}</p>
                        @if ($show_services)
                            <ul class="t1-footer__links">
                                @foreach ($footer_services as $service)
                                    <li>
                                        <a href="{{ route('service.detail', ['url' => $service['url']]) }}">
                                            {!! $service['title'] !!}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        @if ($show_namads)
                            <ul class="t1-footer__trust namad">
                                @if (@$settings['enamad'] != null)
                                    <li>
                                        <figure class="m-0">
                                            {!! $settings['enamad'] !!}
                                        </figure>
                                    </li>
                                @endif
                                @foreach ($footer_valids as $valid)
                                    <li>
                                        <figure class="m-0">
                                            {!! $valid !!}
                                        </figure>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif

                @if (count($footer_links) > 0 || !$allNull)
                    <nav class="t1-footer__col" aria-label="دسترسی سریع">
                        <p class="t1-footer__label">دسترسی سریع</p>
                        <ul class="t1-footer__links">
                            @foreach ($footer_links as $footer_item)
                                <li>
                                    <a href="{{ url(trim(@$footer_item['url'])) }}">
                                        {{ @$footer_item['title'] }}
                                    </a>
                                </li>
                            @endforeach
                            @if (!$allNull)
                                <li>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#timeModal">
                                        ساعات کاری
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </nav>
                @endif

                @if ($show_categories)
                    <nav class="t1-footer__col" aria-label="{{ trim(strip_tags(@$settings['category_title_footer'] ?: 'دسته‌بندی')) }}">
                        <p class="t1-footer__label">{{ @$settings['category_title_footer'] }}</p>
                        <ul class="t1-footer__links">
                            @foreach ($footer_product_categories as $footer_product_category)
                                <li>
                                    <a href="{{ \App\Library\SiteUrl::category($footer_product_category) }}">
                                        {!! $footer_product_category['title'] !!}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </nav>
                @endif

                @if ($show_contact)
                    <div class="t1-footer__col t1-footer__col--contact">
                        <p class="t1-footer__label">همراه ما باشید</p>
                        <ul class="t1-footer__contact">
                            @if ($address !== '')
                                <li>
                                    @if ($map_url !== '')
                                        <a href="{{ $map_url }}"
                                           class="t1-footer__meta"
                                           target="_blank"
                                           rel="noopener noreferrer nofollow">
                                            <span class="t1-footer__meta-icon" aria-hidden="true">
                                                <i class="bi bi-geo-alt"></i>
                                            </span>
                                            <span>{{ $address }}</span>
                                        </a>
                                    @else
                                        <span class="t1-footer__meta">
                                            <span class="t1-footer__meta-icon" aria-hidden="true">
                                                <i class="bi bi-geo-alt"></i>
                                            </span>
                                            <span>{{ $address }}</span>
                                        </span>
                                    @endif
                                </li>
                            @endif
                            @if ($email !== '')
                                <li>
                                    <a href="mailto:{{ $email }}" class="t1-footer__meta" dir="ltr">
                                        <span class="t1-footer__meta-icon" aria-hidden="true">
                                            <i class="bi bi-envelope"></i>
                                        </span>
                                        <span>{{ $email }}</span>
                                    </a>
                                </li>
                            @endif
                            @foreach ($phone_numbers as $key => $phone)
                                <li>
                                    <a href="tel:{{ $phone }}"
                                       class="t1-footer__meta{{ $key === 0 ? ' t1-footer__meta--lead' : '' }}"
                                       dir="ltr"
                                       id="Footer-Call-{{ $key + 1 }}">
                                        <span class="t1-footer__meta-icon" aria-hidden="true">
                                            <i class="bi bi-telephone"></i>
                                        </span>
                                        <span>@toPersianNumber($phone)</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                        @if ($has_branches)
                            <div class="t1-footer__branch" id="t1FooterBranch" v-cloak>
                                <label class="t1-footer__branch-label" for="t1-footer-branch-select">انتخاب شعبه</label>
                                <div class="t1-footer__branch-wrap">
                                    <select id="t1-footer-branch-select"
                                            class="t1-footer__branch-select"
                                            v-model="mainBranch"
                                            aria-label="انتخاب شعبه">
                                        <option v-for="branch in branches" :key="branch.id" :value="branch">
                                            @{{ branch.title }}
                                        </option>
                                    </select>
                                    <i class="bi bi-chevron-down" aria-hidden="true"></i>
                                </div>
                                <a v-if="mainBranch && mainBranch.map"
                                   class="t1-footer__map"
                                   :href="mainBranch.map"
                                   target="_blank"
                                   rel="nofollow noopener noreferrer">
                                    <i class="bi bi-geo" aria-hidden="true"></i>
                                    مسیریابی از روی نقشه
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <div class="t1-footer__legal">
                <p class="t1-footer__legal-copy">
                    کلیه حقوق این وب‌سایت متعلق به
                    <span class="t1-footer__legal-brand">{{ $site_name !== '' ? $site_name : 'این مجموعه' }}</span>
                    می‌باشد.
                </p>
                @if (request()->getHost() !== 'netisho.com')
                    <p class="t1-footer__legal-credit">طراحی سایت و سئو سایت: همگامان</p>
                @endif
            </div>
        </div>

        <div class="t1-footer__ground" aria-hidden="true">
            <svg class="t1-footer__plateau" viewBox="0 0 1440 168" preserveAspectRatio="none">
                <path class="t1-footer__hill t1-footer__hill--back"
                      d="M0 92C168 44 312 128 480 78C672 18 792 118 1008 64C1176 22 1308 78 1440 48V168H0Z"/>
                <path class="t1-footer__hill t1-footer__hill--front"
                      d="M0 118C192 72 336 148 528 102C744 48 888 142 1104 96C1248 64 1356 108 1440 86V168H0Z"/>
            </svg>
        </div>
    </div>
</footer>

@if (isset($settings['work_hours']))
    @php
        $t1_hours_title = trim((string) (@$settings['work_hours_first_page_title'] ?? ''));
        if ($t1_hours_title === '') {
            $t1_hours_title = 'ساعات کاری';
        }
        $t1_hours_cta = trim((string) (@$settings['work_hours_first_page_text'] ?? ''));
        $t1_hours_main_tel = trim((string) (@$settings['main_phone_number'] ?? ($phone_numbers[0] ?? '')));
        $t1_today_dow = (int) now()->dayOfWeek;
        $t1_day_dow = [
            'شنبه' => 6,
            'یکشنبه' => 0,
            'دوشنبه' => 1,
            'سهشنبه' => 2,
            'سه‌شنبه' => 2,
            'سه شنبه' => 2,
            'چهارشنبه' => 3,
            'پنجشنبه' => 4,
            'پنج‌شنبه' => 4,
            'پنج شنبه' => 4,
            'جمعه' => 5,
        ];
    @endphp
    <div class="modal fade t1-hours" id="timeModal" tabindex="-1" aria-labelledby="t1-hours-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered t1-hours__dialog">
            <div class="modal-content t1-hours__content">
                <button type="button"
                        class="t1-hours__close"
                        data-bs-dismiss="modal"
                        aria-label="بستن">
                    <i class="bi bi-x-lg" aria-hidden="true"></i>
                </button>

                <header class="t1-hours__head">
                    <span class="t1-hours__mark" aria-hidden="true">
                        <i class="bi bi-clock"></i>
                    </span>
                    <div>
                        <p class="t1-hours__eyebrow">برنامه هفته</p>
                        <h2 class="t1-hours__title" id="t1-hours-title">{{ $t1_hours_title }}</h2>
                    </div>
                </header>

                <ul class="t1-hours__days">
                    @foreach ($work_hours as $day_name => $work_hour)
                        @php
                            $t1_day_key = str_replace(["\u{200C}", '‌', ' '], '', (string) $day_name);
                            $t1_is_today = ($t1_day_dow[$t1_day_key] ?? null) === $t1_today_dow;
                            $t1_is_closed = ($work_hour['from'] ?? null) == null && ($work_hour['to'] ?? null) == null;
                        @endphp
                        <li class="t1-hours__day{{ $t1_is_today ? ' is-today' : '' }}{{ $t1_is_closed ? ' is-closed' : '' }}">
                            <span class="t1-hours__day-name">
                                {{ $day_name }}
                                @if ($t1_is_today)
                                    <span class="t1-hours__today">امروز</span>
                                @endif
                            </span>
                            @if ($t1_is_closed)
                                <span class="t1-hours__off">تعطیل</span>
                            @else
                                <span class="t1-hours__range" dir="ltr">
                                    @toPersianNumber($work_hour['to'])
                                    <span class="t1-hours__until">تا</span>
                                    @toPersianNumber($work_hour['from'])
                                </span>
                            @endif
                        </li>
                    @endforeach
                </ul>

                @if ($t1_hours_main_tel !== '')
                    <a href="tel:{{ $t1_hours_main_tel }}" class="t1-hours__call">
                        <span class="t1-hours__call-icon" aria-hidden="true">
                            <i class="bi bi-telephone"></i>
                        </span>
                        <span class="t1-hours__call-copy">
                            @if ($t1_hours_cta !== '')
                                <span class="t1-hours__call-label">{{ $t1_hours_cta }}</span>
                            @endif
                            <span class="t1-hours__call-phones">
                                @foreach ($phone_numbers as $phone)
                                    <span dir="ltr">@toPersianNumber($phone)</span>
                                @endforeach
                            </span>
                        </span>
                    </a>
                @endif
            </div>
        </div>
    </div>
@endif

@push('vue')
    @include('layouts.main.blocks.main-vue', ['element_id' => 'menu'])
    @if ($has_branches)
        @include('layouts.main.blocks.main-vue', ['element_id' => 't1FooterBranch'])
    @endif
    @include('layouts.main.blocks.search-vue')
@endpush
