@php
    $pkgServices = $package->services;
    $pkgPhone = trim((string) (@$settings['main_phone_number'] ?? ''));
    $pkgChat = trim((string) (@$settings['online_support_link'] ?? ''));
@endphp
<aside class="pkg-dossier__aside">
    <div class="pkg-dossier__ticket">
        <span class="pkg-dossier__perf" aria-hidden="true"></span>
        @include('pages.package-detail._partials.package-price')

        @if($pkgServices->count() > 0)
            <div class="pkg-dossier__includes">
                <p class="pkg-dossier__aside-title">خدمات این بسته</p>
                <ul>
                    @foreach($pkgServices as $package_service)
                        <li>
                            @if(!empty($package_service['url']))
                                <a href="{{ route('service.detail', ['url' => $package_service['url']]) }}">
                                    <i class="bi bi-check2" aria-hidden="true"></i>
                                    <span>{{ $package_service['title'] }}</span>
                                </a>
                            @else
                                <span class="pkg-dossier__includes-plain">
                                    <i class="bi bi-check2" aria-hidden="true"></i>
                                    <span>{{ $package_service['title'] }}</span>
                                </span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="pkg-dossier__aside-actions">
            @if($pkgChat !== '')
                <a href="{{ $pkgChat }}" class="t1-btn t1-btn--ghost">
                    <i class="bi bi-chat-dots" aria-hidden="true"></i>
                    پشتیبانی آنلاین
                </a>
            @elseif($pkgPhone !== '')
                <a href="tel:{{ $pkgPhone }}" class="t1-btn t1-btn--ghost">
                    <i class="bi bi-telephone" aria-hidden="true"></i>
                    تماس با کارشناسان
                </a>
            @endif
            <a href="{{ route('package.list') }}" class="t1-link-arrow">
                همه پکیج‌ها
            </a>
        </div>
    </div>
</aside>
