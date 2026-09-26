@if ($sample['description'])
<section class="sk-page pt-0">
    <div class="container">
        <article class="sk-prose sk-prose--page" aria-label="توضیحات نمونه کار">
            <p class="sk-prose__eyebrow">جزئیات نمونه کار</p>
            <div class="sk-prose__body content">
                {!! $sample['description'] !!}
            </div>
        </article>
    </div>
</section>
@endif
