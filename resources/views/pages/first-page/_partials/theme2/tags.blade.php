@if(count($tags) > 0)
    <section class="tags">
        @foreach($tags as $tag)
            @if(count($tag['products']) > 0)
                <section class="offer" data-offer-swiper aria-labelledby="home-tag-{{ $tag['id'] }}">
                    <div class="container">
                        <div class="offer-head" data-reveal>
                            <div class="offer-head__copy">
                                <span class="offer-head__eyebrow">مجموعه ویژه</span>
                                <h2 id="home-tag-{{ $tag['id'] }}" class="offer-head__title">
                                    @if(@$tag->item_first_page_icon)
                                        <img src="{{ $tag->item_first_page_icon }}" alt="" class="offer-head__icon" width="36" height="36">
                                    @endif
                                    {{ $tag['title'] }}
                                </h2>
                            </div>
                            <div class="offer-head__actions">
                                <div class="offer-nav" role="group" aria-label="ورق زدن محصولات {{ $tag['title'] }}">
                                    <button type="button" class="offer-nav__btn offer-nav__btn--prev" aria-label="قبلی">
                                        <i class="bi bi-chevron-right" aria-hidden="true"></i>
                                    </button>
                                    <button type="button" class="offer-nav__btn offer-nav__btn--next" aria-label="بعدی">
                                        <i class="bi bi-chevron-left" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <a href="{{ route('tag.detail', ['url' => $tag['url']]) }}" class="offer-head__all">
                                    مشاهده همه
                                </a>
                            </div>
                        </div>
                        <div class="swiper swiper-offer-new" data-reveal>
                            <div class="swiper-wrapper">
                                @foreach($tag['products'] as $product)
                                    <div class="swiper-slide">
                                        @include('layouts.common.product.product-box', ['product' => $product])
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>
                @php
                    $tagId = $tag->id ?? $tag['id'] ?? null;
                    $entry = $tagId !== null ? (($tagHighlightMap ?? [])[$tagId] ?? null) : null;
                    $tagHighlightList = collect($entry['desktop'] ?? []);
                    if ($tagHighlightList->isEmpty()) {
                        $tagHighlightList = collect($entry['mobile'] ?? []);
                    }
                @endphp
                @include('pages.first-page._partials.components.first-page-tag-highlights', [
                    'mobileList' => $tagHighlightList,
                    'desktopList' => $tagHighlightList,
                ])
            @endif
        @endforeach
    </section>
@endif
