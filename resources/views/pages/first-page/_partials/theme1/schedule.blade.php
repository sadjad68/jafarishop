@if(isset($settings['work_hours']))
    @php
        $allNull = collect($settings['work_hours'])->every(function($day) {
        return is_null($day['from']) && is_null($day['to']);
    });
    @endphp
    @if(!$allNull)
<section class="time-detail " style="background-image: url('{{$settings['work_hours_big_image']}}');">
    <div class="right-bubble"></div>
    <div class="container">
        <div class="position-relative">
            <div class="col-xxl-6 col-xl-6 col-lg-8 col-md-8 col-12 p-0 m-auto">
                <div class="time-table p-lg-5 p-sm-4 py-4 p-3">
                    <div class="title-section mb-sm-5 mb-4 text-center">
                        <p class="fw-bolder h2 mb-3 title"></p>
                        <p class="font-th op-lighter short-des">
                            {{@$settings['work_hours_first_page_title']}}
                        </p>
                    </div>
                    <ul class="m-0 px-lg-5 px-sm-4 px-2 mx-lg-5 mx-0 p-0 ">
                        @foreach($settings['work_hours'] as $day_name => $work_hour)
                            <li class="d-flex align-items-center justify-content-between mb-3 pb-3 ">
                                <span class="font-th">{{$day_name}}</span>
                                @if($work_hour['from'] == null && $work_hour['to'] == null)
                                    <span class="font-th">تعطیل</span>
                                @else
                                    <span class="font-th">
                                        @toPersianNumber($work_hour['from']) تا @toPersianNumber($work_hour['to'])
                                    </span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                    @if($settings['phone_numbers'])
                        <a href="tel:{{$settings['main_phone_number']}}" class="btn btn-arrow-two btn-time mt-5 d-flex align-items-sm-center align-items-end text-start justify-content-between w-100 h-rotate dynamic-color2">
                            <div class="d-flex align-items-center me-2">
                            <i class="bi bi-telephone-fill d-flex fs-5 me-4 "></i>
                                <div>
                                    <span class="d-flex align-items-center mb-2 small fw-bolder">
                                        {{@$settings['work_hours_first_page_text']}}
                                    </span>
                                    <div class="d-flex align-items-center flex-wrap ">
                                        @foreach($settings['phone_numbers'] as $phone)
                                            <p class="mb-0 op-light small font-th me-3" dir="ltr"> @toPersianNumber($phone) </p>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endif
                </div>
            </div>
            <div class="img-time d-md-block d-none">
                <img src="{{$settings['work_hours_small_image']}}" title="img-time" alt="img-time" loading="lazy">
            </div>
        </div>
    </div>
    <div class="left-bubble"></div>
</section>
@endif
   
@endif
{{--@include('pages.first-page._partials.banner_two')--}}