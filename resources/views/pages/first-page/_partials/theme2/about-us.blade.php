@php
    $support_link = trim((string) (@$settings['online_support_link'] ?? ''));
    $support_external = (bool) preg_match('#^https?://#i', $support_link);
    $phone_numbers = is_array(@$settings['phone_numbers']) ? $settings['phone_numbers'] : [];
    $has_chat = trim((string) (@$settings['online_support_text'] ?? '')) !== ''
        || $support_link !== ''
        || trim((string) (@$settings['online_support_title'] ?? '')) !== '';
    $has_call = count($phone_numbers) > 0
        || trim((string) (@$settings['support_call_text'] ?? '')) !== '';
    $has_desk = $has_chat || $has_call;
    $support_title = trim((string) (@$settings['online_support_title'] ?? ''));
    if ($support_title === '') {
        $support_title = 'ارتباط آنلاین';
    }
@endphp
<section class="about-us" aria-labelledby="home-about-title">
    <div class="container">
        <div class="about-us__slab{{ $has_desk ? '' : ' about-us__slab--solo' }}" data-reveal-group>
            <div class="about-us__story" data-reveal>
                <h2 id="home-about-title" class="about-us__title">
                    {!! @$settings['first_page_first_title'] !!}
                </h2>
                <div class="about-us__text">
                    {!! @$settings['first_page_first_text'] !!}
                </div>
                <a href="{{ route('us.about') }}" class="about-us__more">درباره ما</a>
            </div>

            @if ($has_desk)
                <aside class="about-us__desk" data-reveal aria-label="پشتیبانی">
                    <p class="about-us__desk-kicker">پشتیبانی</p>

                    @if ($has_chat)
                        <div class="about-us__channel about-us__channel--chat">
                            <div class="about-us__channel-copy">
                                @if (!empty($settings['online_support_text']))
                                    <h3 class="about-us__channel-title">{{ $settings['online_support_text'] }}</h3>
                                @endif
                                @if (!empty($settings['online_support_hours']))
                                    <p class="about-us__channel-hours">{{ $settings['online_support_hours'] }}</p>
                                @endif
                            </div>
                            @if ($support_link !== '' || !empty($settings['online_support_title']))
                                @if ($support_link !== '')
                                    <a href="{{ $support_link }}"
                                       class="about-us__go"
                                       @if ($support_external) target="_blank" rel="noopener noreferrer" @endif>
                                @else
                                    <span class="about-us__go about-us__go--static">
                                @endif
                                    <span class="about-us__go-icon">
                                        @if (!empty($settings['online_support_image']))
                                            <img src="{{ $settings['online_support_image'] }}"
                                                 alt=""
                                                 width="28"
                                                 height="28"
                                                 loading="lazy">
                                        @else
                                            <i class="bi bi-chat-dots" aria-hidden="true"></i>
                                        @endif
                                    </span>
                                    <span class="about-us__go-label">{{ $support_title }}</span>
                                    @if ($support_link !== '')
                                        <i class="bi bi-chevron-left about-us__go-chevron" aria-hidden="true"></i>
                                    @endif
                                @if ($support_link !== '')
                                    </a>
                                @else
                                    </span>
                                @endif
                            @endif
                        </div>
                    @endif

                    @if ($has_call)
                        <div class="about-us__channel about-us__channel--call">
                            <div class="about-us__channel-copy">
                                @if (!empty($settings['support_call_text']))
                                    <h3 class="about-us__channel-title">{{ $settings['support_call_text'] }}</h3>
                                @endif
                                @if (!empty($settings['support_call_hours']))
                                    <p class="about-us__channel-hours">{{ $settings['support_call_hours'] }}</p>
                                @endif
                            </div>
                            @if (count($phone_numbers) > 0)
                                @if (!empty($settings['phones_first_page_text']))
                                    <p class="about-us__numbers-label">{{ $settings['phones_first_page_text'] }}</p>
                                @endif
                                <ul class="about-us__numbers">
                                    @foreach ($phone_numbers as $phone)
                                        <li>
                                            <a href="tel:{{ $phone }}" class="about-us__number">
                                                <i class="bi bi-telephone" aria-hidden="true"></i>
                                                <span dir="ltr">@toPersianNumber($phone)</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endif
                </aside>
            @endif
        </div>
    </div>
</section>
