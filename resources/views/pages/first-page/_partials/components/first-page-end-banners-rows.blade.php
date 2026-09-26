@php
    use App\Modules\Banner\Services\HighlightService;
    $device = $device ?? 'desktop';
    $rows = $firstPageEndBannerRows ?? HighlightService::firstPageEndBannerRowDefinitions();
    $map = $highlightData ?? [];
    $hasSection = false;
    foreach ($rows as $row) {
        if (HighlightService::rowHasAnyHighlight($map, $device, $row['places'])) {
            $hasSection = true;
            break;
        }
    }
    $colClasses = [
        1 => 'col-12 p-md-2 p-1',
        2 => 'col-md-6 col-12 p-md-2 p-1',
        3 => 'col-md-4 col-12 p-md-2 p-1',
        4 => 'col-lg-3 col-6 p-md-2 p-1',
    ];
@endphp
@if ($hasSection)
    <section class="banners" data-reveal>
        <div class="container">
            @foreach ($rows as $row)
                @if (HighlightService::rowHasAnyHighlight($map, $device, $row['places']))
                    @php
                        $filledPlaces = [];
                        foreach ($row['places'] as $placeKey) {
                            $candidate = $map[$device][$placeKey] ?? null;
                            if (HighlightService::highlightHasStoredImage($candidate)) {
                                $filledPlaces[] = $placeKey;
                            }
                        }
                        $colClass = $colClasses[count($filledPlaces)] ?? ($row['tile_col_class'] ?? 'col p-md-2 p-1');
                    @endphp
                    <div class="row w-100 m-0">
                        @foreach ($filledPlaces as $placeKey)
                            @include('pages.first-page._partials.components.first-page-end-highlight-tile', [
                                'highlight' => $map[$device][$placeKey],
                                'colClass' => $colClass,
                            ])
                        @endforeach
                    </div>
                @endif
            @endforeach
        </div>
    </section>
@endif
