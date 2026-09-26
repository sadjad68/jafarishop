{{--
    Shared section header for the theme1 homepage.
    Params:
    - t1_eyebrow (string)  short Persian label
    - t1_title   (string)  raw/rich title (rendered with {!! !!})
    - t1_desc    (string|null) optional raw/rich description
    - t1_center  (bool)    center the block
    - t1_class   (string)  extra classes on the wrapper
    - t1_title_id (string) optional id on the title for aria-labelledby
--}}
@php
    $t1_center = $t1_center ?? false;
    $t1_class = $t1_class ?? '';
    $t1_title_id = $t1_title_id ?? '';
@endphp
<div class="t1-head @if($t1_center) t1-head--center @endif {{ $t1_class }}" data-reveal>
    <span class="t1-head__eyebrow">{{ $t1_eyebrow }}</span>
    <p class="t1-head__title" @if($t1_title_id !== '') id="{{ $t1_title_id }}" @endif>{!! $t1_title !!}</p>
    @if(!empty($t1_desc))
        <p class="t1-head__desc">{!! $t1_desc !!}</p>
    @endif
</div>
