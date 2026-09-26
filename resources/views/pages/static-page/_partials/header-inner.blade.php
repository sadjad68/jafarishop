<section class="hero position-relative">
    <div class="over-hero service-image position-relative d-flex align-items-sm-end align-items-end justify-content-start " style=" background-image: url('{{@$settings['image_header']}}') !important;">
        <div class="hero-content container">
            <div class="position-relative z-3">
                <h1 class="mb-sm-1 mb-1 fw-bold light">
                    {{@$page->getH1PagesAttribute($page)}}
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{route('index')}}" class="d-flex align-items-cente text-light font-re">
                                <i class="bi bi-house d-flex me-1"></i>
                                خانه
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-light" aria-current="page">{{$page['title']}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>
