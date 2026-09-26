@if(count($related_blogs) > 0)
    <section class="blog-article__panel related-blog" aria-labelledby="blog-related-posts-title">
        <h2 id="blog-related-posts-title" class="blog-article__panel-title">مطالب مرتبط</h2>
        <div class="related-blog__cards">
            @foreach(collect($related_blogs)->take(5) as $related_blog)
                @include('layouts.common.blog.blog-card', ['blog' => $related_blog, 'compact' => true])
            @endforeach
        </div>
        @if(@$blog->category)
            <a href="{{ route('blog.list', ['url' => $blog->category->url]) }}" class="blog-article__more">
                مشاهده همه مطالب این موضوع
                <i class="bi bi-arrow-left" aria-hidden="true"></i>
            </a>
        @endif
    </section>
@endif
