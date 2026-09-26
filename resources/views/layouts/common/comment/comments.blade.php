@php
    $commentCount = isset($comments) ? $comments->count() : 0;
    $commentCountFa = \App\Modules\General\Helper\NumberHelper::latin2PersianDigit((string) $commentCount);
@endphp
<section class="comments sk-comments" id="comments">
    <div class="container">
        <header class="title-section sk-comments__head position-relative mb-sm-5 mb-4 text-center col-xxl-5 col-xl-6 col-lg-7 col-md-12 m-auto p-0">
            <span class="sk-comments__eyebrow">گفتگو</span>
            <h2 class="fw-bolder h2 mb-4 title sk-comments__title">نظرات کاربران</h2>
            <p class="sk-comments__count">
                @if($commentCount > 0)
                    {{ $commentCountFa }} نظر ثبت‌شده
                @else
                    هنوز نظری ثبت نشده
                @endif
            </p>
        </header>
        @include('layouts.common.comment._partials.comment-base')
    </div>
</section>
