@if(count($faqs) > 0)
<section class="sk-faq mt-5">
    <div class="container">
        <div class="sk-section-head">
            <span class="sk-section-head__eyebrow">پرسش‌ها</span>
            <h2 class="sk-section-head__title">سوالات متداول</h2>
        </div>
        <div class="accordion" id="accordionExample">
            @foreach($faqs as $key => $faq)
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button {{ $key == 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapse{{$faq->id}}" aria-expanded="{{ $key == 0 ? 'true' : 'false' }}" aria-controls="collapse{{$faq->id}}">
                        {{$faq['question']}}
                    </button>
                </h3>
                <div id="collapse{{$faq->id}}" class="accordion-collapse collapse {{ $key == 0 ? 'show' : '' }}" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        {!! $faq['answer'] !!}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
