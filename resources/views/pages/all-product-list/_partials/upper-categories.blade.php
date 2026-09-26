@if(count($children) > 0)
    <div class="plp-subcats">
        <div class="plp-subcats__scroll">
            <button type="button" class="plp-subcats__scroll-btn plp-subcats__scroll-btn--left" aria-label="اسکرول زیردسته‌ها به چپ">
                <i class="bi bi-chevron-left d-flex"></i>
            </button>
            <div class="plp-subcats__track">
                @foreach($children as $child)
                    <a href="{{ \App\Library\SiteUrl::category($child) }}"
                       class="plp-subcats__chip h-rotate">
                        <img src="{{ @$child->getImage('medium') }}" alt="{{ @$child['title'] }}"
                             title="{{ @$child['title'] }}" loading="lazy" class="plp-subcats__thumb">
                        <div class="plp-subcats__meta">
                            <p class="plp-subcats__name font-md">{{ @$child['title'] }}</p>
                            @if(@$child['product_counts'] != 0)
                                <p class="plp-subcats__count font-num-r">{{ @$child['product_counts'] }} محصول</p>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
            <button type="button" class="plp-subcats__scroll-btn plp-subcats__scroll-btn--right" aria-label="اسکرول زیردسته‌ها به راست">
                <i class="bi bi-chevron-right d-flex"></i>
            </button>
        </div>
    </div>
@endif
