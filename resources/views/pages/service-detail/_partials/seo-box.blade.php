@if($service['description'] != null)
<section class="sk-page pt-0">
    <div class="container">
        <article class="sk-prose sk-prose--page" aria-label="توضیحات خدمت">
            <p class="sk-prose__eyebrow">جزئیات خدمت</p>
            <div class="sk-prose__body content">
                {!! $service['description'] !!}
            </div>
        </article>
    </div>
</section>
@endif
