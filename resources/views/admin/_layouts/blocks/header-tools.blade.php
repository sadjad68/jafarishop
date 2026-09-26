@php
    $notifyCount = ($order_count ?? 0) + ($comment_count ?? 0) + ($contact_count ?? 0);
    $notifyId = $notifyId ?? 'drop-alert';
    $includeLogout = $includeLogout ?? false;
@endphp
<div class="admin-topbar-cluster">
    <div class="dropdown">
        <button class="admin-icon-btn admin-avatar-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="حساب کاربری">
            <img src="{{ asset('assets/admin/images/user2.webp') }}" alt="">
            <span class="admin-status-dot"></span>
        </button>
        <ul class="dropdown-menu admin-user-menu p-2">
            <li class="admin-user-menu-head">
                <img src="{{ asset('assets/admin/images/user2.webp') }}" alt="">
                <span>
                    <strong>{{ Auth::user()->full_name }}</strong>
                    @if(Auth::user()->mobile)
                        <small>{{ Auth::user()->mobile }}</small>
                    @endif
                </span>
            </li>
            <li>
                <a class="dropdown-item admin-user-menu-logout" href="{{ url('admin/logout') }}">
                    <i class="bi bi-power"></i>
                    خروج از حساب
                </a>
            </li>
        </ul>
    </div>

    <div class="dropdown">
        <button class="admin-icon-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="اعلان‌ها">
            <i class="bi bi-bell"></i>
            @if($notifyCount > 0)
                <span class="admin-notify-badge">{{ $notifyCount }}</span>
            @endif
        </button>
        @if($notifyCount > 0)
            <ul class="dropdown-menu admin-notify-menu p-2" id="{{ $notifyId }}">
                @if($order_count > 0)
                    <li>
                        <a class="admin-notify-item" href="{{ route('admin.order.index') }}">
                            <span class="admin-notify-ico"><i class="bi bi-receipt"></i></span>
                            <span>
                                <strong>سفارش جدید</strong>
                                <small>تعداد {{ $order_count }} سفارش جدید ثبت شده است.</small>
                            </span>
                        </a>
                    </li>
                @endif
                @if($contact_count > 0)
                    <li>
                        <a class="admin-notify-item" href="{{ route('admin.contact.index') }}">
                            <span class="admin-notify-ico"><i class="bi bi-envelope"></i></span>
                            <span>
                                <strong>تماس با ما جدید</strong>
                                <small>تعداد {{ $contact_count }} تماس با ما جدید ثبت شده است.</small>
                            </span>
                        </a>
                    </li>
                @endif
                @if($comment_count > 0)
                    <li>
                        <a class="admin-notify-item" href="{{ route('admin.comment.index') }}">
                            <span class="admin-notify-ico"><i class="bi bi-chat-dots"></i></span>
                            <span>
                                <strong>نظر جدید</strong>
                                <small>تعداد {{ $comment_count }} نظر جدید ثبت شده است.</small>
                            </span>
                        </a>
                    </li>
                @endif
            </ul>
        @endif
    </div>

    @include('admin._layouts.blocks.theme-toggle')

    <a class="admin-icon-btn" href="{{ url('/admin') }}" data-bs-toggle="tooltip" data-bs-title="داشبورد">
        <i class="bi bi-speedometer2"></i>
    </a>
    <a class="admin-icon-btn" href="{{ url('admin/setting') }}" data-bs-toggle="tooltip" data-bs-title="تنظیمات">
        <i class="bi bi-gear"></i>
    </a>
    @if($includeLogout)
        <a class="admin-icon-btn admin-icon-btn-logout" href="{{ url('admin/logout') }}" data-bs-toggle="tooltip" data-bs-title="خروج">
            <i class="bi bi-power"></i>
        </a>
    @endif
</div>
