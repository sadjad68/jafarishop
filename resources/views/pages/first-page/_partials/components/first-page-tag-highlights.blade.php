@php
    use App\Modules\Banner\Services\HighlightService;
    $mobileList = collect($mobileList ?? [])->filter(fn ($b) => HighlightService::highlightHasStoredImage($b));
    $desktopList = collect($desktopList ?? [])->filter(fn ($b) => HighlightService::highlightHasStoredImage($b));
    $list = $desktopList->isNotEmpty() ? $desktopList : $mobileList;
    $size = HighlightService::tagBannerSize();
@endphp
@if ($list->isNotEmpty())
    <section class="banners banners--in-tags" data-reveal>
        <div class="container">
            @foreach ($list as $banner)
                @if ($banner->link)
                    <a href="{{ $banner->link }}" class="d-block text-decoration-none" target="_blank" rel="noopener noreferrer">
                        <img src="{{ $banner->image }}" class="w-100 h-auto" width="{{ $size['width'] }}" height="{{ $size['height'] }}"
                            alt="{{ $banner->title ?? 'banner' }}" loading="lazy">
                    </a>
                @else
                    <img src="{{ $banner->image }}" class="w-100 h-auto" width="{{ $size['width'] }}" height="{{ $size['height'] }}"
                        alt="{{ $banner->title ?? 'banner' }}" loading="lazy">
                @endif
            @endforeach
        </div>
    </section>
@endif
