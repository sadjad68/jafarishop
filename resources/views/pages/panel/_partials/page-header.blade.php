@php
    $icon = $icon ?? null;
    $title = $title ?? '';
    $subtitle = $subtitle ?? null;
    $badge = $badge ?? null;
    $variant = $variant ?? 'default';
@endphp
<div class="panel-card panel-card--header panel-card--{{ $variant }} mb-3">
    <div class="panel-card__head {{ isset($actions) ? 'panel-card__head--split' : '' }}">
        <div>
            <h1 class="panel-card__title">
                @if($icon)
                    <span class="panel-card__title-icon"><i class="bi {{ $icon }}"></i></span>
                @endif
                {{ $title }}
                @if($badge)
                    <span class="panel-card__badge font-num-r">{{ $badge }}</span>
                @endif
            </h1>
            @if($subtitle)
                <p class="panel-card__meta">{{ $subtitle }}</p>
            @endif
        </div>
        @if(isset($actions))
            <div class="panel-card__actions sk-cta-row">
                {!! $actions !!}
            </div>
        @endif
    </div>
</div>
