@php
    $tone = $tone ?? 'brand';
    $link = $link ?? null;
    $linkLabel = $linkLabel ?? 'مشاهده';
    $value = $value ?? '—';
    $label = $label ?? '';
    $title = $title ?? '';
    $icon = $icon ?? 'bi-circle';
@endphp
<div class="panel-stat panel-stat--{{ $tone }}">
    <div class="panel-stat__top">
        <span class="panel-stat__icon"><i class="bi {{ $icon }}"></i></span>
        @if(!empty($editLink))
            <a href="{{ $editLink }}" class="panel-stat__edit" title="ویرایش">
                <i class="bi bi-pencil-square"></i>
            </a>
        @endif
    </div>
    <p class="panel-stat__title">{{ $title }}</p>
    <p class="panel-stat__value font-num-r">{{ $value }}</p>
    <p class="panel-stat__label">{{ $label }}</p>
    @if($link)
        <a href="{{ $link }}" class="panel-stat__link">
            {{ $linkLabel }}
            <i class="bi bi-arrow-left-short"></i>
        </a>
    @endif
</div>
