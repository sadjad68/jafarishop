{{-- بنر تک‌اسلات جایگاه (هایلایت) --}}
@php
    /** @var \App\Modules\Banner\Entities\Highlight|null $highlight */
@endphp
@if ($highlight && \App\Modules\Banner\Services\HighlightService::highlightHasStoredImage($highlight))
    @if ($highlight->link)
        <a class="text-decoration-none"
            href="{{ $highlight->link }}" target="_blank" rel="noopener noreferrer">
            <img src="{{ $highlight->image }}" class="w-100 h-auto" alt="{{ $highlight->title ?? 'banner' }}" loading="lazy">
        </a>
    @else
        <img src="{{ $highlight->image }}" class="w-100 h-auto" alt="{{ $highlight->title ?? 'banner' }}" loading="lazy">
    @endif
@endif
