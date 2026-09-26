@if(count($services) > 0)
    <div class="sk-service-grid">
        @foreach($services as $row)
            @include('layouts.common.service.service-card', ['service' => $row])
        @endforeach
    </div>
@else
    <p class="sk-empty">هنوز خدمتی برای نمایش وجود ندارد.</p>
@endif
