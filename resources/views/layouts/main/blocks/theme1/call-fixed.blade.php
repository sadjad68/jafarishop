<div class="call-fixed">
    <div class="contaner">
        <div class="position-relative">
            <div class="btn-call m-3">
                @mobile
                    <a href="tel:{{ @$settings['ads_number'] }}" class=" text-decoration-none align-items-center justify-content-center d-flex font-e-bold">
                        <img src="{{ asset('assets/site/images/telephone.png') }}" width="30" height="30" class="me-2" alt="{{ @$settings['ads_text'] ? @$settings['ads_text'] : 'تماس جهت مشاوره' }}" />
                        {{ @$settings['ads_text'] ? @$settings['ads_text'] : 'تماس جهت مشاوره' }}
                    </a>
                @else
                    <p class=" text-decoration-none align-items-center justify-content-center d-flex font-e-bold">
                        <img src="{{ asset('assets/site/images/telephone.png') }}" width="30" height="30" class="me-2" alt="{{ @$settings['ads_text'] ? @$settings['ads_text'] : 'تماس جهت مشاوره' }}" />
                        {{ @$settings['ads_number'] }}
                    </p>
                @endmobile
            </div>
        </div>
    </div>
</div>
