@if(count($product_categories) > 0)
    <section class="product-category t1-section t1-section--ink">
        <div class="container">
            <div class="category-rail">
                <div class="category-rail__head">
                    @include('pages.first-page._partials.theme1._section-head', [
                        't1_eyebrow' => 'دسته‌بندی',
                        't1_title' => 'محصولات ' . @$settings['first_page_shop_title'],
                    ])
                    <a href="{{route('category.list')}}" class="t1-link-arrow h-rotate">
                        مشاهده همه
                    </a>
                </div>
                <div class="category-rail__body" data-reveal>
                    <div class="swiper swiper-categories">
                        <div class="swiper-wrapper">
                            @foreach($product_categories as $product_category)
                                <div class="swiper-slide">
                                    <div class="cat-card">
                                        <a href="{{ \App\Library\SiteUrl::category($product_category) }}">
                                            <div class="img-cat">
                                                <img src="{{@$product_category->getImage("big")}}"
                                                     alt="{{@$product_category['title']}}"
                                                     title="{{@$product_category['title']}}" loading="lazy">
                                            </div>
                                            <p class="name dynamic-color">
                                                {{@$product_category['title']}}
                                            </p>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
