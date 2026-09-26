@php
    $icon = $icon ?? 'bi-circle';
    $label = $label ?? '';
@endphp
<div class="panel-info-row {{ !empty($wide) ? 'panel-info-row--wide' : '' }}">
    <div class="panel-info-row__label">
        <span class="panel-info-row__icon"><i class="bi {{ $icon }}"></i></span>
        {{ $label }}
    </div>
    <div class="panel-info-row__value">{!! $value ?? '' !!}</div>
</div>
