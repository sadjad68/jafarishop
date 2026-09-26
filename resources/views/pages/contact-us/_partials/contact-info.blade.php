<div class="sk-contact__panel">
    <h2 class="sk-contact__title">{{ $settings['phone_call'] ?: 'راه‌های ارتباط' }}</h2>
    <ul class="sk-contact__list">
        @if ($settings['main_phone_number'])
            <li>
                <a href="tel:{{ $settings['main_phone_number'] }}" class="sk-contact__item">
                    <span class="sk-contact__icon" aria-hidden="true"><i class="bi bi-telephone-fill"></i></span>
                    <span>
                        <p class="sk-contact__label">تلفن تماس</p>
                        <p class="sk-contact__value" dir="ltr">@toPersianNumber($settings['main_phone_number'])</p>
                    </span>
                </a>
            </li>
        @endif
        @if (count($settings['phone_numbers']) > 0)
            <li>
                <div class="sk-contact__item">
                    <span class="sk-contact__icon" aria-hidden="true"><i class="bi bi-headset"></i></span>
                    <span>
                        <p class="sk-contact__label">پشتیبانی</p>
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            @foreach ($settings['phone_numbers'] as $phone)
                                <a href="tel:{{ $phone }}" class="sk-contact__value" dir="ltr">
                                    @toPersianNumber($phone)
                                </a>
                            @endforeach
                        </div>
                    </span>
                </div>
            </li>
        @endif
        @if ($settings['email'])
            <li>
                <a href="mailto:{{ $settings['email'] }}" class="sk-contact__item">
                    <span class="sk-contact__icon" aria-hidden="true"><i class="bi bi-envelope-fill"></i></span>
                    <span>
                        <p class="sk-contact__label">ایمیل</p>
                        <p class="sk-contact__value" dir="ltr">{{ $settings['email'] }}</p>
                    </span>
                </a>
            </li>
        @endif
        @if ($settings['address'])
            <li>
                @if(!empty($settings['map']))
                    <a href="{{ $settings['map'] }}" class="sk-contact__item" target="_blank" rel="noopener noreferrer">
                @else
                    <div class="sk-contact__item">
                @endif
                    <span class="sk-contact__icon" aria-hidden="true"><i class="bi bi-geo-alt-fill"></i></span>
                    <span>
                        <p class="sk-contact__label">آدرس</p>
                        <p class="sk-contact__value">{{ $settings['address'] }}</p>
                    </span>
                @if(!empty($settings['map']))
                    </a>
                @else
                    </div>
                @endif
            </li>
        @endif
        @if (count($socials) > 0)
            <li>
                <div class="sk-contact__item">
                    <span class="sk-contact__icon" aria-hidden="true"><i class="bi bi-share-fill"></i></span>
                    <span>
                        <p class="sk-contact__label">شبکه‌های اجتماعی</p>
                        <ul class="sk-contact__socials">
                            @foreach ($socials as $social)
                                <li>
                                    <a href="{{ $social['link'] }}" rel="nofollow noopener noreferrer" target="_blank">
                                        <img width="32" height="32"
                                             src="{{ asset('assets/site/images/socials') . '/' . $social['icon'] . '.png' }}"
                                             alt="{{ $social['icon'] }}">
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </span>
                </div>
            </li>
        @endif
    </ul>
</div>
