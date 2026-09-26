@if(count($galleries) > 0)
<section class="gallery t1-section t1-section--ink">
    <div class="container">
            @include('pages.first-page._partials.theme1._section-head', [
            't1_eyebrow' => 'گالری تصاویر',
            't1_title' => @$settings['first_page_gallery_title'],
            't1_center' => true,
        ])

            <div class="gallery-grid" data-reveal-group>
                @foreach ($galleries as $index => $gallery)
                    @if ($index < 6)
                        <div class="gallery-grid__item" data-reveal>
                            <img src="{{$gallery['item_image']}}" alt="{{$gallery['title']}}" title="{{$gallery['title']}}" loading="lazy">
                            @if(!empty($gallery['title']))
                                <span class="gallery-grid__caption">{{ $gallery['title'] }}</span>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>

        <div class="gallery-cta text-center">
            <a href="{{route('gallery.category')}}" class="t1-link-arrow h-rotate">
                {!! @$settings['gallery_button'] !!}
            </a>
        </div>
    </div>
</section>
@endif
