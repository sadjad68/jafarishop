<div class="panel-profile">
    <div class="panel-profile__avatar-wrap">
        <img src="{{ \Illuminate\Support\Facades\Auth::user()->getDashboardAvatar() }}"
             alt="{{ \Illuminate\Support\Facades\Auth::user()->full_name }}"
             class="panel-profile__avatar">
        <span class="panel-profile__status" title="آنلاین"></span>
    </div>
    <div class="panel-profile__body">
        <p class="panel-profile__name">{{ \Illuminate\Support\Facades\Auth::user()->full_name }}</p>
        @if(\Illuminate\Support\Facades\Auth::user()->mobile)
            <p class="panel-profile__phone font-num-r">{{ \Illuminate\Support\Facades\Auth::user()->mobile }}</p>
        @endif
        <span class="panel-profile__badge">عضو فعال</span>
    </div>
    @if (!isset(\Illuminate\Support\Facades\Auth::user()->birthday))
        <a href="{{ route('panel.profile') }}" onclick="localStorage.setItem('editBirthday', '1')"
           class="panel-profile__hint d-lg-flex d-none">
            <i class="bi bi-cake2"></i>
            ثبت تاریخ تولد
        </a>
    @else
        <div class="panel-profile__hint d-lg-flex d-none">
            <i class="bi bi-cake2"></i>
            <span class="font-num-r">{{ jdate('Y/m/d', \Illuminate\Support\Facades\Auth::user()->birthday) }}</span>
            <a href="{{ route('panel.profile') }}" class="panel-profile__hint-edit"
               onclick="localStorage.setItem('editBirthday', '1')" title="ویرایش">
                <i class="bi bi-pen"></i>
            </a>
        </div>
    @endif
</div>
