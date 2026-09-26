<div class="plp-empty">
    <img src="{{ asset('assets/site/images/empty-states/Photos_empty.png') }}" class="plp-empty__icon" alt="" loading="lazy">
    <p class="plp-empty__title">{{ $message ?? 'محصولی برای نمایش وجود ندارد.' }}</p>
    <a href="{{ route('product.get-all') }}" class="plp-empty__action">
        مشاهده همه محصولات
    </a>
</div>
