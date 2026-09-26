<div class="admin-order-section {{ !empty($fullWidth) ? 'admin-order-section--full' : '' }}">
    <div class="admin-order-section__head">
        <span class="admin-order-section__icon"><i class="bi {{ $icon ?? 'bi-info-circle' }}"></i></span>
        <h3 class="admin-order-section__title">{{ $title ?? '' }}</h3>
        @if(!empty($extra))
            <div class="admin-order-section__extra">{!! $extra !!}</div>
        @endif
    </div>
    <div class="admin-order-section__body">
        @foreach(($rows ?? []) as $row)
            @include('admin.order.order._partials.info-row', $row)
        @endforeach
    </div>
</div>
