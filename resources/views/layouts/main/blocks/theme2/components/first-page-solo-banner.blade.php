@php
    /** @var \App\Modules\Banner\Entities\Highlight|null $data */
@endphp
@if ($data && \App\Modules\Banner\Services\HighlightService::highlightHasStoredImage($data))
    @if ($data->link)
        <a href="{{ $data->link }}" class="d-block text-decoration-none" target="_blank" rel="noopener noreferrer">
            <img src="{{ $data->image }}" class="w-100 h-auto" width="1400" height="308"
                alt="{{ $data->title ?? 'banner' }}" loading="lazy">
        </a>
    @else
        <img src="{{ $data->image }}" class="w-100 h-auto" width="1400" height="308"
            alt="{{ $data->title ?? 'banner' }}" loading="lazy">
    @endif
@endif
