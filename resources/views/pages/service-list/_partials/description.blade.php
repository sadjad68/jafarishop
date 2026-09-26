@if(isset($service) && $service['description'] != null)
    <div class="container">
        <section class="sk-prose" aria-label="درباره این خدمت">
            <p class="sk-prose__eyebrow">درباره این خدمت</p>
            <div class="sk-prose__body content">
                {!! $service['description'] !!}
            </div>
        </section>
    </div>
@endif
