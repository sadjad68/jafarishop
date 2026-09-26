@if(count($services) > 0)
    @php
        $t1_services = collect($services)->take(6);
    @endphp
    <section class="services t1-section t1-section--brand" aria-labelledby="t1-services-title">
        <div class="container">
            <div class="services-head">
                @include('pages.first-page._partials.theme1._section-head', [
                    't1_eyebrow' => 'خدمات ما',
                    't1_title' => @$settings['first_page_service_title'],
                    't1_desc' => @$settings['first_page_service_text'],
                    't1_class' => 'services-head__copy',
                    't1_title_id' => 't1-services-title',
                ])
                <a href="{{ route('service.list') }}" data-reveal class="t1-link-arrow services-head__all">
                    همه خدمات
                </a>
            </div>
        </div>
        <div class="services-bands" data-reveal-group>
            @foreach($t1_services as $service)
                @php
                    $service_excerpt = trim(strip_tags($service['short_description'] ?? $service['description'] ?? ''));
                @endphp
                <a href="{{ route('service.detail', ['url' => $service['url']]) }}"
                   data-reveal
                   class="services-band">
                    <img src="{{ $service['image'] }}"
                         class="services-band__img"
                         alt="{{ $service['title'] }}"
                         title="{{ $service['title'] }}"
                         width="960"
                         height="640"
                         loading="{{ $loop->first ? 'eager' : 'lazy' }}">
                    <div class="services-band__copy">
                        <span class="services-band__index" aria-hidden="true">{{ sprintf('%02d', $loop->iteration) }}</span>
                        <p class="services-band__title">{{ $service['title'] }}</p>
                        @if($service_excerpt !== '')
                            <p class="services-band__excerpt">{{ \Illuminate\Support\Str::limit($service_excerpt, 160) }}</p>
                        @endif
                        <span class="services-band__cta">
                            مشاهده خدمت
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@endif
