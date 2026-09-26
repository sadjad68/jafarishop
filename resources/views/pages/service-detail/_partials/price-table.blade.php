@if(count($fees) > 0)
<section class="sk-page pt-0">
    <div class="container">
        <div class="sk-section-head">
            <span class="sk-section-head__eyebrow">تعرفه</span>
            <h2 class="sk-section-head__title">قیمت {{ $service['title'] }}</h2>
        </div>
        <div class="sk-price">
            <div class="sk-price__head">
                <span>توضیحات</span>
                <span>قیمت <span class="sk-price__note">(تومان)</span></span>
            </div>
            @foreach($fees as $fee)
                <div class="sk-price__row">
                    <span>{{ $fee['description'] }}</span>
                    <span>از {{ number_format($fee['minimum_price']) }} تا {{ number_format($fee['maximum_price']) }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
