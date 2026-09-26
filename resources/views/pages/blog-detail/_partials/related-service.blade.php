@if(count($services) > 0)
    <section class="blog-article__panel" aria-labelledby="blog-related-services-title">
        <h2 id="blog-related-services-title" class="blog-article__panel-title">خدمات مرتبط</h2>
        <ul class="blog-article__services">
            @foreach($services as $service)
                <li>
                    <a href="{{ route('service.detail', ['url' => $service['url']]) }}">
                        {{ $service['title'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </section>
@endif
