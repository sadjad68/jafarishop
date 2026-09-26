{{-- کاشی بنر انتهای صفحه (کلاس ستون از ردیف والد) --}}
@php
    /** @var \App\Modules\Banner\Entities\Highlight|null $highlight */
    $colClass = $colClass ?? 'col-12 p-2';
@endphp
@if ($highlight && \App\Modules\Banner\Services\HighlightService::highlightHasStoredImage($highlight))
    <div class="{{ $colClass }}">
        @if ($highlight->link)
            <a href="{{ $highlight->link }}" class="d-block text-decoration-none" target="_blank" rel="noopener noreferrer">
                <img src="{{ $highlight->image }}" class="w-100 h-auto" alt="{{ $highlight->title ?? 'banner' }}" loading="lazy">
            </a>
        @else
            <img src="{{ $highlight->image }}" class="w-100 h-auto" alt="{{ $highlight->title ?? 'banner' }}" loading="lazy">
        @endif
    </div>
@endif
