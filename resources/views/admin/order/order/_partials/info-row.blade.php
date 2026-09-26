@php
    $icon = $icon ?? 'bi-circle';
    $label = $label ?? '';
@endphp
<div class="admin-order-row {{ !empty($wide) ? 'admin-order-row--wide' : '' }}">
    <div class="admin-order-row__label">
        <span class="admin-order-row__icon"><i class="bi {{ $icon }}"></i></span>
        {{ $label }}
    </div>
    <div class="admin-order-row__value">{!! $value ?? '' !!}</div>
</div>
