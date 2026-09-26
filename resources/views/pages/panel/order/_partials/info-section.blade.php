<div class="panel-info-section {{ !empty($fullWidth) ? 'panel-info-section--full' : '' }} {{ !empty($layout) ? 'panel-info-section--' . $layout : '' }}">
    <div class="panel-info-section__head">
        <span class="panel-info-section__icon"><i class="bi {{ $icon ?? 'bi-info-circle' }}"></i></span>
        <h3 class="panel-info-section__title">{{ $title ?? '' }}</h3>
    </div>
    <div class="panel-info-section__body">
        @foreach(($rows ?? []) as $row)
            @include('pages.panel.order._partials.info-row', $row)
        @endforeach
    </div>
</div>
