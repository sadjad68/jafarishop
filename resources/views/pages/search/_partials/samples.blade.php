<div class="samples">
    <div class="title-section mb-4 px-md-2 px-1">
        <p class="fw-bolder h5 mb-1 title">نمونه کارها</p>
        <p class="font-th small op-lighter short-des">
            نمونه کارهای یافت شده مرتبط با "{{$search}}"
        </p>
    </div>
    <div class="row w-100 m-0">
        @forelse($result['data'] as $searched_portfolio)
        <div class="col-xxl-4 col-xl-4 col-lg-4 col-sm-6 p-md-2 p-1">
            <div class="list-card position-relative">
                <img src="{{$searched_portfolio['image']}}" class="w-100" alt="{{$searched_portfolio['title']}}" loading="lazy">
                <div class="overlay">
                    <p class="font-bold mb-1">
                        {{$searched_portfolio['title']}}
                    </p>
                    @if ($searched_portfolio['url'] != null)
                        <a href="{{ route('portfolio.detail', ['url' => $searched_portfolio['url']]) }}" class="btn btn-two w-100 btn-sm py-2 px-3 mt-1">
                            مشاهده جزئیات
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @empty
            {{--        //Todo ui: خالی بودن لیست--}}
        @endforelse
    </div>
    @include('pages.search._partials._pagination')
</div>
