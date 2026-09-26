@php
    use App\Modules\Banner\Services\HighlightService;
    $map = $highlightData ?? [];
    $solo = HighlightService::highlightHasStoredImage($map['desktop']['solo'] ?? null)
        ? ($map['desktop']['solo'] ?? null)
        : ($map['mobile']['solo'] ?? null);
@endphp
@if (HighlightService::highlightHasStoredImage($solo))
    <section class="banners" data-reveal>
        <div class="container">
            @include('layouts.main.blocks.theme2.components.first-page-solo-banner', [
                'data' => $solo,
            ])
        </div>
    </section>
@endif
