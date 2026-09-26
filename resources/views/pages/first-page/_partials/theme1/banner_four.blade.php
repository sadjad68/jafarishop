<div class="banner mt-5">
    <div class="container">
        <div class="row w-100 m-0">
            <div class="col p-1">
                <a href="#" class="rounded-4 overflow-hidden d-block ">
                    @mobile
                    <img src="{{asset('assets/site/images/banner/one-mobile.jpg')}}" class="w-100 d-lg-none d-block">
                    @else
                        <img src="{{asset('assets/site/images/banner/one-1.webp')}}" class="w-100 d-lg-block d-none">
                        @endmobile
                </a>
            </div>
        </div>
    </div>
</div>
