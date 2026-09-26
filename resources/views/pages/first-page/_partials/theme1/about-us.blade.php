@php
    $t1_phone = trim((string) (@$settings['main_phone_number'] ?? ''));
    $t1_phone_label = trim((string) (@$settings['support_call_text'] ?? ''));
    if ($t1_phone_label === '') {
        $t1_phone_label = 'تماس با ما';
    }
    $t1_has_branches = isset($branches) && count($branches) > 0;
    $t1_has_visit = $t1_has_branches || $t1_phone !== '';
@endphp
<section class="about-us t1-section t1-section--brand" id="about_us_branch" aria-labelledby="t1-about-title">
    <div class="container">
        <div class="about-us__layout @if(!$t1_has_visit) about-us__layout--solo @endif">
            <div class="about-us__story" data-reveal>
                @include('pages.first-page._partials.theme1._section-head', [
                    't1_eyebrow' => 'درباره ما',
                    't1_title' => @$settings['first_page_first_title'],
                    't1_title_id' => 't1-about-title',
                ])
                @if(!empty($settings['first_page_first_text']))
                    <div class="about-us__text">
                        {!! $settings['first_page_first_text'] !!}
                    </div>
                @endif
                <div class="about-us__actions">
                    <a href="{{ route('us.about') }}" class="t1-btn t1-btn--solid">
                        بیشتر بخوانید
                    </a>
                </div>
            </div>
            @if($t1_has_visit)
                @include('layouts.common.branches', [
                    't1_phone' => $t1_phone,
                    't1_phone_label' => $t1_phone_label,
                    't1_has_branches' => $t1_has_branches,
                ])
            @endif
        </div>
    </div>
</section>
@if($t1_has_branches)
    @push('vue')
        @include('layouts.main.blocks.main-vue', ['element_id' => 'about_us_branch'])
    @endpush
@endif
