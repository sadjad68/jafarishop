@php
    $t1_pkg_show_head = $t1_pkg_show_head ?? false;
    $t1_pkg_show_all = $t1_pkg_show_all ?? false;
    $t1_pkg_show_assist = $t1_pkg_show_assist ?? true;
    $t1_pkg_lead_first = $t1_pkg_lead_first ?? true;
    $t1_pkg_allow_empty = $t1_pkg_allow_empty ?? false;
    $t1_pkg_tight = $t1_pkg_tight ?? false;
    $t1_pkg_title_id = $t1_pkg_title_id ?? 't1-packages-title';
    $t1_pkg_phone = trim((string) (@$settings['main_phone_number'] ?? ''));
    $t1_pkg_chat = trim((string) (@$settings['online_support_link'] ?? ''));
    $t1_pkg_phones = $settings['phone_numbers'] ?? [];
    $t1_pkg_has_assist = $t1_pkg_show_assist && ($t1_pkg_phone !== '' || $t1_pkg_chat !== '');
@endphp
@if(count($packages) > 0 || $t1_pkg_allow_empty)
    <section class="packages t1-section @if($t1_pkg_tight) t1-section--tight @endif"
             aria-labelledby="{{ $t1_pkg_title_id }}">
        <div class="container">
            @if($t1_pkg_show_head)
                <div class="packages-head">
                    @include('pages.first-page._partials.theme1._section-head', [
                        't1_eyebrow' => 'پکیج‌های ما',
                        't1_title' => @$settings['first_page_package_title'],
                        't1_desc' => @$settings['first_page_package_text'],
                        't1_class' => 'packages-head__copy',
                        't1_title_id' => $t1_pkg_title_id,
                    ])
                    @if($t1_pkg_show_all)
                        <a href="{{ route('package.list') }}" data-reveal class="t1-link-arrow packages-head__all">
                            همه پکیج‌ها
                        </a>
                    @endif
                </div>
            @endif

            @if(count($packages) > 0)
                <div class="pkg-rack" data-reveal-group>
                    @foreach($packages as $package)
                        @include('layouts.common.package.theme1-ticket', [
                            'package' => $package,
                            'isLead' => $t1_pkg_lead_first && $loop->first,
                        ])
                    @endforeach
                </div>
            @else
                <p class="sk-empty">هنوز پکیجی برای نمایش وجود ندارد.</p>
            @endif

            @if($t1_pkg_has_assist)
                <div class="pkg-assist" data-reveal-group>
                    @if($t1_pkg_phone !== '')
                        <a href="tel:{{ $t1_pkg_phone }}" data-reveal class="pkg-assist__item">
                            <span class="pkg-assist__icon" aria-hidden="true">
                                <i class="bi bi-telephone"></i>
                            </span>
                            <span class="pkg-assist__copy">
                                <span class="pkg-assist__label">{{ $settings['support_call_text'] }}</span>
                                @if(!empty($settings['support_call_hours']))
                                    <span class="pkg-assist__meta">{{ $settings['support_call_hours'] }}</span>
                                @endif
                                @if(is_array($t1_pkg_phones) && count($t1_pkg_phones) > 0)
                                    <span class="pkg-assist__nums" dir="ltr">
                                        @foreach($t1_pkg_phones as $phone)
                                            <span>@toPersianNumber($phone)</span>
                                        @endforeach
                                    </span>
                                @endif
                            </span>
                        </a>
                    @endif
                    @if($t1_pkg_chat !== '')
                        <a href="{{ $t1_pkg_chat }}" data-reveal class="pkg-assist__item">
                            <span class="pkg-assist__icon" aria-hidden="true">
                                <i class="bi bi-chat-dots"></i>
                            </span>
                            <span class="pkg-assist__copy">
                                <span class="pkg-assist__label">{{ $settings['online_support_text'] ?? $settings['online_support_title'] }}</span>
                                @if(!empty($settings['online_support_hours']))
                                    <span class="pkg-assist__meta">{{ $settings['online_support_hours'] }}</span>
                                @endif
                                @if(!empty($settings['online_support_title']) && ($settings['online_support_text'] ?? '') !== $settings['online_support_title'])
                                    <span class="pkg-assist__hint">{{ $settings['online_support_title'] }}</span>
                                @endif
                            </span>
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </section>
@endif
