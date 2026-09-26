<div class="blogs">
    <div class="title-section mb-4 px-md-2 px-1">
        <p class="fw-bolder h5 mb-1 title">مطالب</p>
        <p class="font-th small op-lighter short-des">
            مطالب یافت شده مرتبط با "{{ $search ?? '' }}"
        </p>
    </div>
    <div class="row w-100 m-0">
        @forelse($blogs ?? [] as $blog)
            <div class="col-xxl-4 col-xl-4 col-lg-4 col-sm-6 p-md-2 p-1">
                @include('layouts.common.blog.blog-card', ['blog' => $blog])
            </div>
        @empty
            {{-- //Todo ui: خالی بودن لیست --}}
        @endforelse
    </div>
</div>
