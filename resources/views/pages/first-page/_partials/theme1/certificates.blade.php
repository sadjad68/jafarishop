@if (count($certificates) > 0)
    <section class="honors t1-section t1-section--brand">
        <div class="container">
            @include('pages.first-page._partials.theme1._section-head', [
                't1_eyebrow' => 'افتخارات ما',
                't1_title' => @$settings['first_page_certificate_title'],
                't1_desc' => @$settings['first_page_certificate_text'],
                't1_center' => true,
            ])
            <div class="swiper swiper-honors" data-reveal>
                <div class="swiper-wrapper py-2">
                    @foreach ($certificates as $certificate)
                        <div class="swiper-slide">
                            <button type="button"
                                    class="honor-card"
                                    data-bs-toggle="modal"
                                    data-bs-target="#honorModal{{ $certificate['id'] }}">
                                <div class="img-box">
                                    <img src="{{ $certificate->getImage() }}" alt="{{ $certificate['title'] }}"
                                        title="{{ $certificate['title'] }}" class="w-100 h-100 honor-img" loading="lazy">
                                </div>
                                <div class="honor-info">
                                    <p class="show-title">{{ $certificate['title'] }}</p>
                                    <span class="btn-honor">مشاهده</span>
                                </div>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    @foreach ($certificates as $certificate)
        <div class="modal fade honors-modal" id="honorModal{{ $certificate['id'] }}" tabindex="-1"
            aria-labelledby="honorModalLabel{{ $certificate['id'] }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-transparent border-0">
                    <div class="modal-header bg-transparent border-0 border-bottom px-0">
                        <p class="modal-title text-white fs-5 font-md" id="honorModalLabel{{ $certificate['id'] }}">
                            {{ $certificate['title'] }}</p>
                        <button type="button" class="btn-close shadow-none btn-honor-modal"
                            data-bs-dismiss="modal" aria-label="بستن"></button>
                    </div>
                    <div class="modal-body px-0">
                        <img src="{{ $certificate->getImage() }}" alt="{{ $certificate['title'] }}"
                            title="{{ $certificate['title'] }}" class="w-100 honor-img">
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif
