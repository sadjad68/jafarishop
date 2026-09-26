@if(count($samples) > 0)
    <section class="samples t1-section">
        <div class="container">
            @include('pages.first-page._partials.theme1._section-head', [
                't1_eyebrow' => 'نمونه کارها',
                't1_title' => @$settings['first_page_sample_title'],
                't1_center' => true,
            ])
            <div class="samples-mosaic" data-reveal-group>
                @foreach($samples as $key => $sample)
                    <a
                        @if(@$sample['url'] != null) href="{{ route('portfolio.detail', ['url' => @$sample['url']]) }}" @endif
                        data-reveal
                        class="item">
                        <img src="{{@$sample->getImage('medium')}}"
                             alt="{{ @$sample['title'] }}"
                             title="{{ @$sample['title'] }}"
                             loading="lazy">
                        @if(@$sample['title'])
                            <span class="samples-caption">{{ $sample['title'] }}</span>
                        @endif
                    </a>
                @endforeach
            </div>
            <div class="samples-cta text-center">
                <a href="{{{route('portfolio.list')}}}" class="t1-link-arrow h-rotate">
                    {!! @$settings['sample_button'] !!}
                </a>
            </div>
        </div>
    </section>
@endif
