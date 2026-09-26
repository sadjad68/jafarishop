<div class="pdp-videos row w-100 m-0 g-3">
    @foreach($videos as $video)
        <div class="col-lg-6 col-12">
            <article class="pdp-videos__card">
                <p class="pdp-videos__title mb-2">
                    <i class="bi bi-play-circle-fill" aria-hidden="true"></i>
                    بررسی عملکرد {{ @$product['title'] }}
                </p>
                <div class="pdp-videos__embed">
                    {!! $video['code'] !!}
                </div>
            </article>
        </div>
    @endforeach
</div>
