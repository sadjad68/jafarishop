@if (count($related_services) > 0)
    <section class="sk-related">
        <div class="container">
            <div class="sk-section-head">
                <span class="sk-section-head__eyebrow">ادامه مسیر</span>
                <h2 class="sk-section-head__title">خدمات مرتبط</h2>
            </div>
            <div class="sk-service-grid">
                @foreach ($related_services as $related)
                    @include('layouts.common.service.service-card', ['service' => $related])
                @endforeach
            </div>
        </div>
    </section>
@endif
