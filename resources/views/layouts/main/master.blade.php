<!DOCTYPE html>
<html lang="fa" dir="rtl" class="is-site-loading">
    @include("layouts.main.blocks.head")
    <body class="theme-{{ $theme_provider->getValue() }}">
    @php
        $theme_provider = app(\App\Modules\General\Helper\ThemeProvider::class);
    @endphp
    @include('layouts.main.blocks.page-loader')
    {!! @$settings['body_codes'] !!}
    @include("layouts.main.blocks." . $theme_provider->getValue() . ".menu")
        {{--Review : سرچ پویا شود--}}
    @if($theme_provider->hasSection('siteSections','search') && $theme_provider->getValue() !== 'theme1')
    @include("layouts.main.blocks." . $theme_provider->getValue() . ".search")
    @endif
    @yield('content')
    @include("layouts.main.blocks." . $theme_provider->getValue() . ".footer")
    @include("layouts.main.blocks.script")
    @include('layouts.common.sweetalert')
    @if($social_icon != null)
        @php
            $fabIcon = $social_icon['icon'] ?? 'social';
            $fabNames = [
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
            $fabName = $fabNames[$fabIcon] ?? 'شبکه‌های اجتماعی';
            $fabLabel = 'گفتگو در ' . $fabName;
        @endphp
        <div class="btn-fix">
            <a href="{{ $social_icon['link'] }}"
               class="site-fab site-fab--{{ $fabIcon }}"
               target="_blank"
               rel="noopener noreferrer"
               aria-label="{{ $fabLabel }}">
                <span class="site-fab__icon">
                    <img src="{{ asset('assets/site/images/socials/' . $fabIcon . '.png') }}"
                         width="44"
                         height="44"
                         alt="">
                </span>
                <span class="site-fab__label">{{ $fabLabel }}</span>
            </a>
        </div>
    @endif
    @if (!request()->is('product/*'))
    @if($theme_provider->getValue() == "theme2" && @$settings['ads_show'] == 1)
        @include('layouts.main.blocks.theme2.call-fixed')
    @endif
    @endif
    </body>
</html>
