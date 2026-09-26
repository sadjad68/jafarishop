<div class="call-fixed">
    <div class="contaner">
        <div class="position-relative">
            <div class="btn-call m-3">
                @mobile
                    <a href="tel:{{ @$settings['ads_number'] }}" class="text-decoration-none align-items-center justify-content-center d-flex gap-3 font-e-bold">
                        <img src="{{ asset('assets/site/images/telephone.png') }}" width="20" height="20" alt="{{ @$settings['ads_text'] ? @$settings['ads_text'] : 'تماس جهت مشاوره' }}" />
                        {{ @$settings['ads_text'] ? @$settings['ads_text'] : 'تماس جهت مشاوره' }}
                    </a>
                @else
                    <span class="text-decoration-none align-items-center justify-content-center d-flex gap-2 font-e-bold">
                        {{ @$settings['ads_number'] }}
                        <img src="{{ asset('assets/site/images/telephone.png') }}" width="20" height="20" alt="{{ @$settings['ads_text'] ? @$settings['ads_text'] : 'تماس جهت مشاوره' }}" />
                    </span>
                @endmobile
            </div>
        </div>
    </div>
</div>
