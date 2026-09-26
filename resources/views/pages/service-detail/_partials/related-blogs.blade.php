@if(count($blogs) > 0)
    <section class="sk-related pb-4" aria-labelledby="service-related-posts-title">
        <div class="container">
            <div class="sk-section-head">
                <span class="sk-section-head__eyebrow">خواندنی‌ها</span>
                <h2 id="service-related-posts-title" class="sk-section-head__title">مطالب مرتبط</h2>
            </div>
            <div class="list-blogs__grid">
                @foreach($blogs as $blog)
                    <article class="list-blogs__item">
                        @include('layouts.common.blog.blog-card', ['blog' => $blog])
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endif
