{{-- scroll to top --}}
{{--<button type="button" id="scroll-to-top" style="display: none" class="btn btn-to-top">--}}
{{--    <i class="bi bi-arrow-up d-flex"></i>--}}
{{--</button>--}}

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
    $contact_title = trim((string) (@$settings['call_to_action_footer_text'] ?? ''));
    if ($contact_title === '') {
        $contact_title = 'ارتباط با ما';
    }
@endphp
<footer class="footer" data-reveal>
    <div class="main-footer">
        <div class="container">
            <div class="footer__grid" data-reveal-group>
                <div class="footer-brand" data-reveal>
                    @if (!empty($settings['logo']))
                        <img src="{{ $settings['logo'] }}"
                             class="footer-brand__logo"
                             alt="{{ @$settings['siteName_fa'] }}"
                             title="{{ @$settings['siteName_fa'] }}"
                             width="140"
                             height="48"
                             loading="lazy">
                    @endif
                    <h2 class="footer-brand__title">
                        {!! @$settings['footer_about_title'] !!}
                    </h2>
                    @if (!empty($settings['footer_about_text']))
                        @php
                            $footer_about_plain = trim(preg_replace('/\s+/u', ' ', strip_tags((string) $settings['footer_about_text'])));
                            $footer_about_long = mb_strlen($footer_about_plain) > 140;
                        @endphp
                        <div id="t2-footer-about" class="footer-brand__about{{ $footer_about_long ? ' is-clamped' : '' }}">
                            <div id="t2-footer-about-text" class="footer-brand__text{{ $footer_about_long ? ' is-clamped' : '' }}">
                                {!! $settings['footer_about_text'] !!}
                            </div>
                            <button type="button"
                                    id="t2-footer-collapse-btn"
                                    class="footer-brand__more"
                                    aria-expanded="false"
                                    aria-controls="t2-footer-about-text"
                                    @if (!$footer_about_long) hidden @endif>
                                <span id="t2-footer-collapse-label">مشاهده بیشتر</span>
                                <i class="bi bi-chevron-down footer-brand__more-icon" aria-hidden="true"></i>
                            </button>
                        </div>
                    @endif
                    @if (@$settings['enamad'] != null || count($footer_valids) > 0)
                        <div class="footer-trust">
                            <p class="footer__label">نمادها</p>
                            <ul>
                                @if (@$settings['enamad'] != null)
                                    <li>
                                        {!! @$settings['enamad'] !!}
                                    </li>
                                @endif
                                @foreach ($footer_valids as $valid)
                                    <li>
                                        {!! @$valid !!}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                @if (count($footer_links) > 0 || !$allNull)
                    <nav class="footer-nav" aria-label="دسترسی سریع" data-reveal>
                        <p class="footer__label">دسترسی سریع</p>
                        <ul>
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

                @if ($show_services || $show_categories)
                    <div class="footer-nav footer-nav--stack" data-reveal>
                        @if ($show_services)
                            <nav aria-label="{{ trim(strip_tags(@$settings['service_title_footer'] ?: 'خدمات')) }}">
                                <p class="footer__label">{{ @$settings['service_title_footer'] }}</p>
                                <ul>
                                    @foreach ($footer_services as $service)
                                        <li>
                                            <a href="{{ route('service.detail', ['url' => $service['url']]) }}">
                                                {!! $service['title'] !!}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </nav>
                        @endif
                        @if ($show_categories)
                            <nav aria-label="{{ trim(strip_tags(@$settings['category_title_footer'] ?: 'دسته‌بندی')) }}">
                                <p class="footer__label">{{ @$settings['category_title_footer'] }}</p>
                                <ul>
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
                    </div>
                @endif

                <div class="footer-contact" data-reveal>
                    <p class="footer__label">{{ $contact_title }}</p>
                    @if (isset($socials) && count($socials) > 0)
                        <p class="footer-contact__hint">ما را در شبکه‌های اجتماعی دنبال کنید</p>
                        <ul class="footer-social">
                            @foreach ($socials as $social)
                                <li>
                                    <a href="{!! $social['link'] !!}"
                                       class="footer-social__link {{ $social['icon'] }}"
                                       target="_blank"
                                       rel="noopener noreferrer nofollow"
                                       aria-label="{{ $social['icon'] }}">
                                        <span>{{ $social['icon'] }}</span>
                                        <img width="22"
                                             height="22"
                                             src="{{ asset('assets/site/images/socials') . '/' . $social['icon'] . '.' . 'png' }}"
                                             alt="">
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    @if (count($phone_numbers) > 0)
                        <div class="footer-phones">
                            @if (!empty($settings['call_to_action_text']))
                                <p class="footer-phones__invite">{{ $settings['call_to_action_text'] }}</p>
                            @endif
                            <div class="footer-phones__row">
                                @foreach ($phone_numbers as $key => $phone)
                                    <a href="tel:{{ $phone }}"
                                       class="footer-phones__link"
                                       dir="ltr"
                                       id="Footer-Call-{{ $key + 1 }}">
                                        <i class="bi bi-telephone" aria-hidden="true"></i>
                                        <span>@toPersianNumber($phone)</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <p class="footer__legal">
                <span>طراحی سایت</span>
                و
                <span>سئو سایت</span>
                :
                <span>همگامان</span>
            </p>
        </div>
    </div>
</footer>
@include('layouts.main.blocks.theme2.hours-modal')
@push('vue')
    @include('layouts.main.blocks.main-vue', ['element_id' => 'menu'])
    @include('layouts.main.blocks.search-vue')
@endpush
