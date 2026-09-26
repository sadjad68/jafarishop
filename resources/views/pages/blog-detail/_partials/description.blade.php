@if($blog['call_to_action'] == 1)
    @include('pages.blog-detail._partials.call-cta')
@endif
<div class="blog-article__body content">
    {!! $blog['description'] !!}
</div>
@if($blog['call_to_action'] == 1)
    @include('pages.blog-detail._partials.call-cta')
@endif
