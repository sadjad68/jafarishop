<div class="side-box">
    <p class="panel-nav-label">منوی حساب</p>
    <ul class="p-0 m-0">
        <li class="list-unstyled">
            <a href="{{ route('panel.dashboard') }}" class="@yield('dashboard')">
                <i class="bi bi-speedometer2"></i>
                <span>داشبورد</span>
            </a>
        </li>
        <li class="list-unstyled">
            <a href="{{ route('panel.profile') }}" class="@yield('profile')">
                <i class="bi bi-person-gear"></i>
                <span>ویرایش اطلاعات</span>
            </a>
        </li>
    </ul>

    <p class="panel-nav-label mt-3">خرید و ارسال</p>
    <ul class="p-0 m-0">
        <li class="list-unstyled">
            <a href="{{ route('panel.orders') }}" class="@yield('order')">
                <i class="bi bi-bag-check"></i>
                <span>سفارشات</span>
            </a>
        </li>
        <li class="list-unstyled">
            <a href="{{ route('panel.address') }}" class="@yield('address')">
                <i class="bi bi-geo-alt"></i>
                <span>آدرس‌ها</span>
            </a>
        </li>
        <li class="list-unstyled">
            <a href="{{ route('basket.cart') }}">
                <i class="bi bi-cart3"></i>
                <span>سبد خرید</span>
            </a>
        </li>
    </ul>

    <ul class="p-0 m-0 mt-3 pt-3 panel-nav-footer">
        <li class="list-unstyled panel-nav-logout">
            <a href="{{ route('auth.logout') }}">
                <i class="bi bi-box-arrow-left"></i>
                <span>خروج از حساب</span>
            </a>
        </li>
    </ul>
</div>
