@if($settings['service_description'] != null)
    <div class="container">
        <section class="sk-prose" aria-label="درباره خدمات">
            <p class="sk-prose__eyebrow">درباره خدمات</p>
            <div class="sk-prose__body content">
                {!! @$settings['service_description'] !!}
            </div>
        </section>
    </div>
@endif
