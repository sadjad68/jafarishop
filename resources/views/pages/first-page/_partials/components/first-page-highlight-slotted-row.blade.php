{{-- ردیف بنر با اسلات‌های دلخواه (کلاس ستون per-slot) --}}
@php
    use App\Modules\Banner\Services\HighlightService;
    $device = $device ?? 'desktop';
    $map = $highlightData ?? [];
    $slots = $slots ?? [];
    $sectionClass = $sectionClass ?? 'banners';
    $innerContainerClass = $innerContainerClass ?? 'container';
    $rowClass = $rowClass ?? 'row w-100 m-0';
    $linkClass = $linkClass ?? 'd-block text-decoration-none';
    $imgClass = $imgClass ?? 'w-100 h-auto';
    $show = HighlightService::slottedPlacesHaveAnyHighlight($map, $device, $slots);
@endphp
@if ($show)
    <section class="{{ $sectionClass }}" data-reveal>
        @if ($innerContainerClass !== '')
            <div class="{{ $innerContainerClass }}">
        @endif
        <div class="{{ $rowClass }}">
            @foreach ($slots as $slot)
                @php $h = $map[$device][$slot['place']] ?? null; @endphp
                @if (HighlightService::highlightHasStoredImage($h))
                    <div class="{{ $slot['wrapper_class'] }}">
                        @if ($h->link)
                            <a href="{{ $h->link }}" class="{{ $linkClass }}" target="_blank" rel="noopener noreferrer">
                                <img src="{{ $h->image }}" class="{{ $imgClass }}"
                                    alt="{{ $h->title ?? 'banner' }}" loading="lazy">
                            </a>
                        @else
                            <div class="{{ $linkClass }}">
                                <img src="{{ $h->image }}" class="{{ $imgClass }}"
                                    alt="{{ $h->title ?? 'banner' }}" loading="lazy">
                            </div>
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
        @if ($innerContainerClass !== '')
            </div>
        @endif
    </section>
@endif
