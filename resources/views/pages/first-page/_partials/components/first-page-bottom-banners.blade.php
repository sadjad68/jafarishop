@php
    use App\Modules\Banner\Services\HighlightService;
    $device = $device ?? 'desktop';
    $slots = $firstPageBottomSlots ?? HighlightService::firstPageBottomBannerSlotDefinitions();
    $map = $highlightData ?? [];
    $show = HighlightService::bottomRowHasAnyHighlight($map, $device, $slots);
@endphp
@if ($show)
    <section class="banners" data-reveal>
        <div class="container">
            <div class="row w-100 m-0">
                @foreach ($slots as $slot)
                    @php $h = $map[$device][$slot['place']] ?? null; @endphp
                    @if (HighlightService::highlightHasStoredImage($h))
                        <div class="{{ $slot['col_class'] }}">
                            @include('pages.first-page._partials.components.first-page-highlight-tile', [
                                'highlight' => $h,
                            ])
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>
@endif
