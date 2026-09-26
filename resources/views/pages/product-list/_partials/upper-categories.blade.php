@if(count($children) > 0)
    <nav class="plp-subcats" aria-label="زیردسته‌ها">
        <p class="plp-subcats__label">زیردسته‌ها</p>
        <div class="plp-subcats__scroll">
            <button type="button" class="plp-subcats__scroll-btn plp-subcats__scroll-btn--left" aria-label="اسکرول زیردسته‌ها به چپ">
                <i class="bi bi-chevron-left d-flex"></i>
            </button>
            <div class="plp-subcats__track">
                @foreach($children as $child)
                    <a href="{{ \App\Library\SiteUrl::category($child) }}"
                       class="plp-subcats__chip">
                        <img src="{{ @$child->getImage('medium') }}" alt="{{ @$child['title'] }}"
                             title="{{ @$child['title'] }}" loading="lazy" class="plp-subcats__thumb" width="48" height="48">
                        <span class="plp-subcats__meta">
                            <span class="plp-subcats__name">{{ @$child['title'] }}</span>
                            @if(@$child['product_counts'] != 0)
                                <span class="plp-subcats__count font-num-r">{{ @$child['product_counts'] }} محصول</span>
                            @endif
                        </span>
                    </a>
                @endforeach
            </div>
            <button type="button" class="plp-subcats__scroll-btn plp-subcats__scroll-btn--right" aria-label="اسکرول زیردسته‌ها به راست">
                <i class="bi bi-chevron-right d-flex"></i>
            </button>
        </div>
    </nav>
@endif
