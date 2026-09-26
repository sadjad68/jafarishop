@if($settings['portfolio_description'])
<div class="container">
    <section class="sk-prose" aria-label="درباره نمونه کارها">
        <p class="sk-prose__eyebrow">درباره نمونه کارها</p>
        <div class="sk-prose__body content">
            {!! $settings['portfolio_description'] !!}
        </div>
    </section>
</div>
@endif
