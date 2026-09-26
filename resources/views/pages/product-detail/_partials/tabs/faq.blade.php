<div class="pdp-faq">
    <h3 class="pdp-tabs__heading">سوالات متداول {{ @$product['title'] }}</h3>
    <div class="accordion pdp-faq__list" id="productFaqAccordion">
        @foreach($faqs as $key => $faq)
            <div class="accordion-item pdp-faq__item">
                <h4 class="accordion-header">
                    <button class="accordion-button pdp-faq__trigger {{ $key == 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse"
                        data-bs-target="#productFaq{{ $faq['id'] }}" aria-expanded="{{ $key == 0 ? 'true' : 'false' }}" aria-controls="productFaq{{ $faq['id'] }}">
                        {{ $faq['question'] }}
                    </button>
                </h4>
                <div id="productFaq{{ $faq['id'] }}" class="accordion-collapse collapse {{ $key == 0 ? 'show' : '' }}" data-bs-parent="#productFaqAccordion">
                    <div class="accordion-body pdp-faq__body">
                        {!! $faq['answer'] !!}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
