<nav class="admin-sidebar-body" aria-label="منوی مدیریت">
<ul class="menu-list admin-nav">
    {{--    <li class="p-3 side-title d-flex align-items-center justify-content-between">--}}
    {{--        CMS--}}
    {{--        <i class="bi bi-chevron-double-down d-flex"></i>--}}

    {{--    </li>--}}

    <li class="admin-nav-section admin-nav-section-home">
        <div class="admin-nav-section-label side-title">خانه</div>
        <ul class="admin-nav-section-list">
            <li>
                <a class="m-link @if( URL::current() == route('admin.dashboard')  ) active @endif "
                   href="{{route('admin.dashboard')}}">
                    <i class="bi bi-speedometer2 fs-5 me-2 d-flex"></i>
                    <span class="title">داشبورد</span>
                </a>
            </li>
            <li>
                <a class="m-link" href="{{ url('/') }}" target="_blank" rel="noopener noreferrer">
                    <i class="bi bi-box-arrow-up-left fs-5 me-2 d-flex" aria-hidden="true"></i>
                    <span class="title">مشاهده سایت</span>
                </a>
            </li>
        </ul>
    </li>

    <li class="admin-nav-section">
        <div class="admin-nav-section-label side-title">فروشگاه</div>
        <ul class="admin-nav-section-list">
    <li class="collapsed">
        @php
            $is_active_product = URL::current() == route('admin.product-category.index')
                || URL::current() == route('admin.product.index');
        @endphp

        <a data-bs-toggle="collapse" data-bs-target="#menu-product" href="#"
           @if($is_active_product) aria-expanded="true" @else aria-expanded="false" @endif
           class="m-link @if(!$is_active_product) collapsed @endif">
            <i class="bi bi-box me-2 d-flex fs-5"></i>
            <span class="title">محصولات</span>
            <i class="bi bi-chevron-left title ms-auto"></i>
        </a>

        <ul class="sub-menu collapse @if($is_active_product) show @endif" id="menu-product">
            <li>
                <a class="ms-link d-flex align-items-center @if( URL::current() == route('admin.product-category.index') ) active @endif"
                   href="{{route('admin.product-category.index')}}">
                    <i class="bi bi-circle d-flex me-2"></i>
                    <span class="title">دسته بندی محصولات</span>
                </a>
            </li>
            <li>
                <a class="ms-link d-flex align-items-center @if( URL::current() == route('admin.product.index') ) active @endif"
                   href="{{route('admin.product.index')}}">
                    <i class="bi bi-circle d-flex me-2"></i>
                    <span class="title">محصولات</span>
                </a>
            </li>
        </ul>
    </li>
    <li class="collapsed">
        @php
            $is_active_request = str_contains(URL::current(), route('admin.availability_notification.index'))
                || str_contains(URL::current(), route('admin.sale_notification.index'));
            $total_notification_count = ($availability_notification_count ?? 0) + ($sale_notification_count ?? 0);
        @endphp

        <a data-bs-toggle="collapse" data-bs-target="#menu-requests" href="#"
           @if($is_active_request) aria-expanded="true" @else aria-expanded="false" @endif
           class="m-link @if(!$is_active_request) collapsed @endif">
            <i class="bi bi-bell me-2 d-flex fs-5"></i>
            <span class="title">درخواست‌ ها</span>

            @if($total_notification_count > 0)
                      <span class="badge bg-label-danger ms-2">
                {{ $total_notification_count }}
            </span>
            @endif

            <i class="bi bi-chevron-left title ms-auto"></i>
        </a>

        <ul class="sub-menu collapse @if($is_active_request) show @endif" id="menu-requests">
            <li>
                <a class="ms-link d-flex align-items-center @if( URL::current() == route('admin.availability_notification.index') ) active @endif"
                   href="{{route('admin.availability_notification.index')}}">
                    <i class="bi bi-bell me-2 d-flex"></i>
                    <span class="title">پیام‌های موجودی</span>
                    <span class="badge bg-label-primary ms-2">
                    {{ @$availability_notification_count }}
                </span>
                </a>
            </li>
            <li>
                <a class="ms-link d-flex align-items-center @if( URL::current() == route('admin.sale_notification.index') ) active @endif"
                   href="{{route('admin.sale_notification.index')}}">
                    <i class="bi bi-percent me-2 d-flex"></i>
                    <span class="title">پیام‌های حراج</span>
                    <span class="badge bg-label-primary ms-2">
                    {{ @$sale_notification_count }}
                </span>
                </a>
            </li>
        </ul>
    </li>
    <li>
        <a class="ms-link d-flex align-items-center @if(URL::current() == route('admin.brand.index')) active @endif"
           href="{{route('admin.brand.index')}}">
            <i class="bi bi-grid me-2 d-flex fs-5"></i>
            <span class="title">
               برند ها
            </span>
        </a>
    </li>
    <li>
        <a class="ms-link d-flex align-items-center @if( URL::current() == route('admin.specification.index')  ) active @endif"
           href="{{route('admin.specification.index')}}">
            <i class="bi bi-funnel me-2 d-flex fs-5"></i>
            <span class="title">
                مشخصات و فیلتر ها
            </span>
        </a>
    </li>
    <li>
        <a class="ms-link d-flex align-items-center @if( URL::current() == route('admin.slogan.index')  ) active @endif"
           href="{{route('admin.slogan.index')}}">
            <i class="bi bi-body-text me-2 d-flex fs-5"></i>
            <span class="title">
                    شعار ها
            </span>
        </a>
    </li>
    <li>
        <a class="ms-link d-flex align-items-center @if( URL::current() == route('admin.discount.index')  ) active @endif"
           href="{{route('admin.discount.index')}}">
            <i class="bi bi-percent me-2 d-flex fs-5"></i>
            <span class="title">
                    تخفیف ها
            </span>
        </a>
    </li>
    <li>
        <a class="ms-link d-flex align-items-center @if(URL::current() == route('admin.basket.index')) active @endif"
           href="{{route('admin.basket.index')}}">
            <i class="bi bi-cart me-2 d-flex fs-5"></i>
            <span class="title">
                    سبد های خرید
                </span>
        </a>

    </li>
    <li>
        <a class="ms-link d-flex align-items-center @if(URL::current() == route('admin.order.index')) active @endif"
           href="{{route('admin.order.index')}}">

            <i class="bi bi-cash-stack me-2 d-flex fs-5"></i>
            <span class="title">
               سفارش ها
            </span>
            <span class="badge bg-label-primary ms-2">
                                          {{@$order_count}}
                {{--                در بلید مستر قرار دارد$order_count--}}
                                        </span>
        </a>

    </li>
    <li class="collapsed">
        @php $is_active = URL::current() == route('admin.bank.index') || URL::current() == route('admin.order-shipping-status.index') ||
        URL::current() == route('admin.state.index') || URL::current() == route('admin.city.index')
         || URL::current() == route('admin.shipping-method.index') | URL::current() == route('admin.address.index')
        @endphp

        <a data-bs-toggle="collapse" data-bs-target="#menu-order" href="#"
           @if($is_active) aria-expanded="true" @else aria-expanded="false" @endif
           class="m-link
                       @if(!$is_active) collapsed @endif">
            <i class="bi bi-cash me-2 d-flex fs-5"></i>
            <span class="title">تنظیمات سفارش</span>
            <i class="bi bi-chevron-left title ms-auto"></i>
        </a>
        <ul class="sub-menu collapse
                     @if($is_active) show @endif"
            id="menu-order">
            <li>
                <a class="ms-link d-flex align-items-center @if( URL::current() == route('admin.bank.index')  ) active @endif "
                   href="{{route('admin.bank.index')}}">
                    <i class="bi bi-circle d-flex me-2"></i>
                    <span class="title">
                          درگاه بانکی
                                </span>
                </a>
            </li>
            <li>
                <a class="ms-link d-flex align-items-center  @if( URL::current() == route('admin.order-shipping-status.index')  ) active @endif "
                   href="{{route('admin.order-shipping-status.index')}}">
                    <i class="bi bi-circle d-flex me-2"></i>
                    <span class="title"> وضعیت سفارش</span>
                </a>
            </li>
            <li>
                <a class="ms-link d-flex align-items-center  @if( URL::current() == route('admin.shipping-method.index')  ) active @endif "
                   href="{{route('admin.shipping-method.index')}}">
                    <i class="bi bi-circle d-flex me-2"></i>
                    <span class="title">روش ارسال</span>
                </a>
            </li>
            <li>
                <a class="ms-link d-flex align-items-center  @if( URL::current() == route('admin.state.index')  ) active @endif "
                   href="{{route('admin.state.index')}}">
                    <i class="bi bi-circle d-flex me-2"></i>
                    <span class="title">استان ها</span>
                </a>
            </li>
            <li>
                <a class="ms-link d-flex align-items-center  @if( URL::current() == route('admin.city.index')  ) active @endif "
                   href="{{route('admin.city.index')}}">
                    <i class="bi bi-circle d-flex me-2"></i>
                    <span class="title">شهر ها</span>
                </a>
            </li>
            <li>
                <a class="ms-link d-flex align-items-center  @if( URL::current() == route('admin.address.index')  ) active @endif "
                   href="{{route('admin.address.index')}}">
                    <i class="bi bi-circle d-flex me-2"></i>
                    <span class="title">آدرس ها</span>
                </a>
            </li>
        </ul>
    </li>


        </ul>
    </li>

    <li class="admin-nav-section">
        <div class="admin-nav-section-label side-title">عمومی</div>
        <ul class="admin-nav-section-list">
    <li class="collapsed">
        @php $is_active = URL::current() == route('admin.user.index') || URL::current() == route('admin.user.index',["type"=>"Admin"]) || URL::current() == route('admin.permission.index') ; @endphp
        <a data-bs-toggle="collapse" data-bs-target="#menu-users" href="#"
           @if($is_active) aria-expanded="true" @else aria-expanded="false" @endif
           class="m-link @if(!$is_active) collapsed @endif"
           id="product-link">
            <i class="bi bi-people me-2 d-flex fs-5"></i>
            <span class="title"> کاربران </span>
            <i class="bi bi-chevron-left title ms-auto"></i>
        </a>
        <ul class="sub-menu collapse
                        @if($is_active) show @endif"
            id="menu-users">
            <li>
                <a class="ms-link d-flex align-items-center @if(URL::current() == route('admin.user.index') && !str_contains(request()->fullUrl(),route('admin.user.index',['type'=>"Admin"])) && !str_contains(request()->fullUrl(),route('admin.user.index',['type'=>"Teacher"]))) active @endif"
                   href="{{route('admin.user.index')}}">
                    <i class="bi bi-circle d-flex me-2"></i>
                    <span class="title"> کاربران
                                </span>
                </a>
            </li>
            <li>
                <a class="ms-link d-flex align-items-center @if(str_contains(request()->fullUrl(),route('admin.user.index',['type'=>"Admin"]))) active @endif"
                   href="{{route('admin.user.index',['type'=>"Admin"])}}">
                    <i class="bi bi-circle d-flex me-2 "></i>
                    <span class="title">مدیران سایت
                                </span>
                </a>
            </li>
            <li>
                <a class="ms-link d-flex align-items-center @if(str_contains(request()->fullUrl(),route('admin.user.index',['type'=>"Teacher"]))) active @endif"
                   href="{{route('admin.user.index',['type'=>"Teacher"])}}">
                    <i class="bi bi-circle d-flex me-2 "></i>
                    <span class="title">پرسنل ها
                                </span>
                </a>
            </li>
            <li>
                <a class="ms-link d-flex align-items-center @if(URL::current() == route('admin.permission.index')) active @endif"
                   href="{{route('admin.permission.index')}}">
                    <i class="bi bi-circle d-flex me-2"></i>
                    <span class="title">
                                    سطح دسترسی
                                </span>
                </a>
            </li>
        </ul>
    </li>
    <li>
        <a class="m-link  @if( URL::current() == route('admin.comment.index')  ) active @endif "
           href="{{route('admin.comment.index')}}">
            <i class="bi bi-chat-square-text me-2 d-flex fs-5"></i>
            <span class="title">نظرات</span>
            <span class="badge bg-label-primary ms-2">
                                          {{@$comment_count}}
                {{--                                در بلید مستر قرار دارد$comment_count--}}

                                        </span>
        </a>
    </li>
    <li>
        <a class="m-link  @if( URL::current() == route('admin.contact.index')  ) active @endif "
           href="{{route('admin.contact.index')}}">
            <i class="bi bi-phone me-2 d-flex fs-5"></i>
            <span class="title">تماس با ما</span>
            <span class="badge bg-label-primary ms-2">
                                          {{@$contact_count}}
                {{--                                در بلید مستر قرار دارد$contact_count--}}

                                        </span>
        </a>
    </li>
    <li class="collapsed">
        @php $is_active = URL::current() == route('admin.blog.index') || URL::current() == route('admin.blog-category.index'); @endphp

        <a data-bs-toggle="collapse" data-bs-target="#menu-blog" href="#"
           @if($is_active) aria-expanded="true" @else aria-expanded="false" @endif
           class="m-link
                       @if(!$is_active) collapsed @endif">
            <i class="bi bi-files me-2 d-flex fs-5"></i>
            <span class="title">مطالب</span>
            <i class="bi bi-chevron-left title ms-auto"></i>
        </a>
        <ul class="sub-menu collapse
                     @if($is_active) show @endif"
            id="menu-blog">
            <li>
                <a class="ms-link d-flex align-items-center @if( URL::current() == route('admin.blog-category.index')  ) active @endif "
                   href="{{url('admin/blog-category')}}">
                    <i class="bi bi-circle d-flex me-2"></i>
                    <span class="title">
                                دسته بندی مطالب
                                </span>
                </a>
            </li>
            <li>
                <a class="ms-link d-flex align-items-center @if( URL::current() == route('admin.blog.index')  ) active @endif"
                   href="{{route('admin.blog.index')}}">
                    <i class="bi bi-circle d-flex me-2"></i>
                    <span class="title">
                                مطالب
                                </span>
                </a>
            </li>
        </ul>
    </li>
    <li>
        <a class="m-link @if(request()->routeIs('admin.video.*')) active @endif"
           href="{{ route('admin.video.index') }}">
            <i class="bi bi-camera-video me-2 d-flex fs-5"></i>
            <span class="title">ویدیوها</span>
        </a>
    </li>
    <li>
        <a class="m-link  @if( URL::current() == route('admin.certificate.index')  ) active @endif "
           href="{{route('admin.certificate.index')}}">
            <i class="bi bi-clipboard-check me-2 d-flex fs-5"></i>
            <span class="title">گواهی نامه ها</span>
        </a>
    </li>
    <li>
        <a class="m-link  @if( URL::current() == route('admin.banner.index')  ) active @endif "
           href="{{route('admin.banner.index')}}">
            <i class="bi bi-back me-2 d-flex fs-5"></i>
            <span class="title">اسلایدر ها </span>
        </a>
    </li>
    @if($themeProvider->hasSection('adminSections','highlight'))
        <li>
            <a class="m-link  @if( URL::current() == route('admin.highlight.index')  ) active @endif "
               href="{{route('admin.highlight.index')}}">
                <i class="bi bi-back me-2 d-flex fs-5"></i>
                <span class="title">بنر ها </span>
            </a>
        </li>
    @endif
    <li>
        <a class="m-link  @if( URL::current() == route('admin.page.index')  ) active @endif "
           href="{{route('admin.page.index')}}">
            <i class="bi bi-book me-2 d-flex fs-5"></i>
            <span class="title">صفحات</span>
        </a>
    </li>
    <li>
        <a class="m-link  @if( URL::current() == route('admin.tag.index')  ) active @endif "
           href="{{route('admin.tag.index')}}">
            <i class="bi bi-tag me-2 d-flex fs-5"></i>
            <span class="title">تگ ها</span>
        </a>
    </li>
    <li>
        <a class="m-link  @if( URL::current() == route('admin.sitemap.index')  ) active @endif "
           href="{{route('admin.sitemap.index')}}">
            <i class="bi bi-diagram-3-fill me-2 d-flex fs-5"></i>
            <span class="title">سایت مپ</span>
        </a>
    </li>
    <li>
        <a class="m-link  @if( URL::current() == route('admin.api.information')  ) active @endif "
           href="{{route('admin.api.information')}}">
            <i class="bi bi-laptop-fill me-2 d-flex fs-5"></i>
            <span class="title">اطلاعات api</span>
        </a>
    </li>
    <li class="collapsed">
        @php $is_active = URL::current() == route('admin.seo.index') || URL::current() == route('admin.redirect.index')
|| URL::current() == route('admin.canonical.index');
        @endphp

        <a data-bs-toggle="collapse" data-bs-target="#menu-seo" href="#"
           @if($is_active) aria-expanded="true" @else aria-expanded="false" @endif
           class="m-link
                       @if(!$is_active) collapsed @endif">
            <i class="bi bi-bookshelf me-2 d-flex fs-5"></i>
            <span class="title">تنظیمات سئو</span>
            <i class="bi bi-chevron-left title ms-auto"></i>
        </a>
        <ul class="sub-menu collapse
                     @if($is_active) show @endif"
            id="menu-seo">
            <li>
                <a class="ms-link d-flex align-items-center @if( URL::current() == route('admin.seo.index')  ) active @endif "
                   href="{{route('admin.seo.index')}}">
                    <i class="bi bi-circle d-flex me-2"></i>
                    <span class="title">
                            سئو صفحات
                                </span>
                </a>
            </li>
            <li>
                <a class="ms-link d-flex align-items-center @if( URL::current() == route('admin.redirect.index')  ) active @endif"
                   href="{{route('admin.redirect.index')}}">
                    <i class="bi bi-circle d-flex me-2"></i>
                    <span class="title">
                              ریدایرکت
                                </span>
                </a>
            </li>
            <li>
                <a class="ms-link d-flex align-items-center @if( URL::current() == route('admin.canonical.index')  ) active @endif"
                   href="{{route('admin.canonical.index')}}">
                    <i class="bi bi-circle d-flex me-2"></i>
                    <span class="title">
                              کنونیکال
                                </span>
                </a>
            </li>

        </ul>
    </li>
    <li class="collapsed">
        @php $is_active = URL::current() == route('admin.setting.index') || URL::current() == route('admin.theme.index')
        || URL::current() == route('admin.social.index')
                        || URL::current() == route('admin.setting-partial.index')

        || URL::current() == route('admin.branch.index');
        @endphp

        <a data-bs-toggle="collapse" data-bs-target="#menu-setting" href="#"
           @if($is_active) aria-expanded="true" @else aria-expanded="false" @endif
           class="m-link
                       @if(!$is_active) collapsed @endif">
            <i class="bi bi-gear me-2 d-flex fs-5"></i>
            <span class="title">تنظیمات عمومی</span>
            <i class="bi bi-chevron-left title ms-auto"></i>
        </a>
        <ul class="sub-menu collapse
                     @if($is_active) show @endif"
            id="menu-setting">
            <li>
                <a class="ms-link d-flex align-items-center @if( URL::current() == route('admin.setting.index')  ) active @endif "
                   href="{{route('admin.setting.index')}}">
                    <i class="bi bi-circle d-flex me-2"></i>
                    <span class="title">
                            تنظیمات
                                </span>
                </a>
            </li>
            <li>
                <a class="ms-link d-flex align-items-center  @if( URL::current() == route('admin.branch.index')  ) active @endif "
                   href="{{route('admin.branch.index')}}">
                    <i class="bi bi-circle d-flex me-2"></i>
                    <span class="title">شعب</span>
                </a>
            </li>
            <li>
                <a class="ms-link d-flex align-items-center  @if( URL::current() == route('admin.social.index')  ) active @endif "
                   href="{{route('admin.social.index')}}">
                    <i class="bi bi-circle d-flex me-2"></i>
                    <span class="title">شبکه های اجتماعی</span>
                </a>
            </li>

            <li>
                <a class="ms-link d-flex align-items-center @if( URL::current() == route('admin.theme.index')  ) active @endif"
                   href="{{route('admin.theme.index')}}">
                    <i class="bi bi-circle d-flex me-2"></i>
                    <span class="title">
                                تم های رنگی
                                </span>
                </a>
            </li>
            {{--            <li>--}}
            {{--                <a class="ms-link d-flex align-items-center @if( URL::current() == route('admin.setting-partial.index')  ) active @endif"--}}
            {{--                   href="{{route('admin.setting-partial.index')}}">--}}
            {{--                    <i class="bi bi-circle d-flex me-2"></i>--}}
            {{--                    <span class="title">--}}
            {{--                       تنظیمات برنامه نویس--}}
            {{--                                </span>--}}
            {{--                </a>--}}
            {{--            </li>--}}
        </ul>
    </li>


        </ul>
    </li>

    <li class="admin-nav-section">
        <div class="admin-nav-section-label side-title">شرکتی</div>
        <ul class="admin-nav-section-list">
    <li class="collapsed">
        @php $is_active = URL::current() == route('admin.gallery.index') || URL::current() == route('admin.gallery-category.index'); @endphp

        <a data-bs-toggle="collapse" data-bs-target="#menu-gallery" href="#"
           @if($is_active) aria-expanded="true" @else aria-expanded="false" @endif
           class="m-link @if(!$is_active) collapsed @endif"
        >
            <i class="bi bi-card-image me-2 d-flex fs-5"></i>
            <span class="title">گالری</span>
            <i class="bi bi-chevron-left title ms-auto"></i>
        </a>
        <ul class="sub-menu collapse
                    @if($is_active) show @endif"
            id="menu-gallery">
            <li>
                <a class="ms-link d-flex align-items-center @if( URL::current() == route('admin.gallery-category.index')  ) active @endif "
                   href="{{route('admin.gallery-category.index')}}">
                    <i class="bi bi-circle d-flex me-2"></i>
                    <span class="title">
                                دسته بندی تصاویر
                                </span>
                </a>
            </li>
            <li>
                <a class="ms-link d-flex align-items-center @if( URL::current() == route('admin.gallery.index')  ) active @endif"
                   href="{{route('admin.gallery.index')}}">
                    <i class="bi bi-circle d-flex me-2"></i>
                    <span class="title">
                                تصاویر
                                </span>
                </a>
            </li>
        </ul>
    </li>
    <li class="collapsed">
        @php $is_active = URL::current() == route('admin.service.index') || URL::current() == route('admin.service.index'); @endphp

        <a data-bs-toggle="collapse" data-bs-target="#menu-service" href="#"
           @if($is_active) aria-expanded="true" @else aria-expanded="false" @endif
           class="m-link @if(!$is_active) collapsed @endif"
        >
            <i class="bi bi-calendar3-event me-2 d-flex fs-5"></i>
            <span class="title">خدمات</span>
            <span class="badge bg-label-primary ms-2">
                        {{@$serviceRequestCount}}
                    </span>
            <i class="bi bi-chevron-left title ms-auto"></i>
        </a>
        <ul class="sub-menu collapse
                    @if($is_active) show @endif"
            id="menu-service">
            <li>
                <a class="ms-link d-flex align-items-center @if( URL::current() == route('admin.service.index')  ) active @endif "
                   href="{{route('admin.service.index')}}">
                    <i class="bi bi-circle d-flex me-2"></i>
                    <span class="title">
                                خدمات
                                </span>
                </a>
            </li>
            <li>
                <a class="ms-link d-flex align-items-center @if( URL::current() == route('admin.service-request.index')  ) active @endif"
                   href="{{route('admin.service-request.index')}}">
                    <i class="bi bi-circle d-flex me-2"></i>
                    <span class="title">
                               درخواست های خدمات
                                </span>
                    <span class="badge bg-label-primary ms-2">
                        {{@$serviceRequestCount}}
                    </span>
                </a>
            </li>
        </ul>
    </li>
    {{--    <li>--}}
    {{--        <a class="m-link  @if( URL::current() == route('admin.service.index')  ) active @endif "--}}
    {{--           href="{{route('admin.service.index')}}">--}}
    {{--            <i class="bi bi-calendar3-event me-2 d-flex fs-5"></i>--}}
    {{--            <span class="title">خدمات</span>--}}
    {{--        </a>--}}
    {{--    </li>--}}
    <li>
        <a class="m-link  @if( URL::current() == route('admin.worksample.index')  ) active @endif "
           href="{{route('admin.worksample.index')}}">
            <i class="bi bi-images me-2 d-flex fs-5"></i>
            <span class="title">نمونه کارها</span>
        </a>
    </li>
    <li>
        <a class="m-link  @if( URL::current() == route('admin.fee.index')  ) active @endif "
           href="{{route('admin.fee.index')}}">
            <i class="bi bi-cash-coin me-2 d-flex fs-5"></i>
            <span class="title">نرخ ها</span>
        </a>
    </li>
    <li>
        <a class="m-link  @if( URL::current() == route('admin.package.index')  ) active @endif "
           href="{{route('admin.package.index')}}">
            <i class="bi bi-card-text me-2 d-flex fs-5"></i>
            <span class="title">پکیج ها</span>
        </a>
    </li>

    {{--    <li class="p-3 side-title d-flex align-items-center justify-content-between">--}}
    {{--        LMS--}}
    {{--        <i class="bi bi-chevron-double-down d-flex"></i>--}}

    {{--    </li>--}}
    {{--    <li class="collapsed">--}}
    {{--        @php $is_active = URL::current() == route('admin.course.index') || URL::current() == route('admin.course-category.index') || URL::current() == route('admin.session.index'); @endphp--}}

    {{--        <a data-bs-toggle="collapse" data-bs-target="#menu-course" href="#"--}}
    {{--           @if($is_active) aria-expanded="true" @else aria-expanded="false" @endif--}}
    {{--           class="m-link--}}
    {{--                       @if(!$is_active) collapsed @endif">--}}
    {{--            <i class="bi bi-mortarboard me-2 d-flex fs-5"></i>--}}
    {{--            <span class="title">دوره ها</span>--}}
    {{--            <i class="bi bi-chevron-left title ms-auto"></i>--}}
    {{--        </a>--}}
    {{--        <ul class="sub-menu collapse  @if($is_active) show @endif"--}}
    {{--            id="menu-course">--}}
    {{--            <li>--}}
    {{--                <a class="ms-link d-flex align-items-center @if( URL::current() == url('/admin/course-category')  ) active @endif "--}}
    {{--                   href="{{url('admin/course-category')}}">--}}
    {{--                    <i class="bi bi-circle d-flex me-2"></i>--}}
    {{--                    <span class="title">--}}
    {{--                                دسته بندی دوره ها--}}
    {{--                                </span>--}}
    {{--                </a>--}}
    {{--            </li>--}}
    {{--            <li>--}}
    {{--                <a class="ms-link d-flex align-items-center @if( URL::current() == url('/admin/course')  ) active @endif"--}}
    {{--                   href="{{url('admin/course')}}">--}}
    {{--                    <i class="bi bi-circle d-flex me-2"></i>--}}
    {{--                    <span class="title">--}}
    {{--                                دوره ها--}}
    {{--                                </span>--}}
    {{--                </a>--}}
    {{--            </li>--}}
    {{--        </ul>--}}
    {{--    </li>--}}
    {{--    <li>--}}
    {{--        <a class="m-link  @if( URL::current() == url('/admin/session')  ) active @endif "--}}
    {{--           href="{{url('admin/session')}}">--}}
    {{--            <i class="bi bi-easel2 me-2 d-flex fs-5"></i>--}}
    {{--            <span class="title">جلسات</span>--}}
    {{--        </a>--}}
    {{--    </li>--}}
        </ul>
    </li>
</ul>
</nav>
<div class="admin-sidebar-foot">
    <a class="m-link admin-nav-logout" href="{{ route('admin.logout') }}">
        <i class="bi bi-box-arrow-right my-0 me-2 d-flex fs-5" aria-hidden="true"></i>
        <span class="title">خروج از پنل</span>
    </a>
</div>
