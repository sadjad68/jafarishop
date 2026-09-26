<div class="services">
    <div class="title-section mb-4 px-md-2 px-1">
        <p class="fw-bolder h5 mb-1 title">خدمات</p>
        <p class="font-th small op-lighter short-des">
            خدمات یافت شده مرتبط با "{{$search}}"
        </p>
    </div>
    <div class="sk-service-grid">
        @forelse($result['data'] as $searched_service)
            @include('layouts.common.service.service-card', ['service' => $searched_service])
        @empty
            {{--        //Todo ui: خالی بودن لیست--}}
        @endforelse
    </div>
    @include('pages.search._partials._pagination')
</div>
