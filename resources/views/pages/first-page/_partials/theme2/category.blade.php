@if(count($product_categories) > 0)
<section class="category" aria-labelledby="home-categories-title">
    <div class="container">
        <div class="category-head" data-reveal>
            <div class="category-head__copy">
                <span class="category-head__eyebrow">مسیر خرید</span>
                <h2 id="home-categories-title" class="category-head__title">خرید بر اساس دسته‌بندی</h2>
            </div>
            <a href="{{ route('category.list') }}" class="category-head__all">
                همه دسته‌ها
            </a>
        </div>
        <nav aria-label="دسته‌بندی محصولات">
            <ul class="category-grid" data-reveal-group>
                @foreach($product_categories as $product_category)
                    @include('layouts.common.category.category-card', ['category' => $product_category, 'reveal' => true])
                @endforeach
            </ul>
        </nav>
    </div>
</section>
@endif
