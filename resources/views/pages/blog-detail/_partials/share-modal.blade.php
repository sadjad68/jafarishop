@php
    $shareUrl = url()->current();
    $shareTitle = (string) data_get($blog, 'title', '');
    $shareMessage = trim($shareTitle !== '' ? $shareTitle . "\n" . $shareUrl : $shareUrl);
    $shareTextOneLine = trim(preg_replace('/\s+/u', ' ', $shareMessage));
    $encUrl = rawurlencode($shareUrl);
    $encTitle = rawurlencode($shareTitle);
    $encMessage = rawurlencode($shareMessage);
    $rubikaIntentText = rawurlencode($shareTextOneLine);
    $rubikaFallbackEnc = rawurlencode('https://web.rubika.ir/');
    $rubikaIntentHref = 'intent://send/#Intent;action=android.intent.action.SEND;type=text/plain;S.android.intent.extra.TEXT=' . $rubikaIntentText . ';package=app.rbmain.a;S.browser_fallback_url=' . $rubikaFallbackEnc . ';end';
    $shareApps = [
        [
            'name' => 'بله',
            'href' => 'https://ble.ir/share/url?url=' . $encUrl . '&text=' . $encTitle,
            'icon' => asset('assets/site/images/social/bale.png'),
            'title' => 'اشتراک در بله',
        ],
        [
            'name' => 'ایتا',
            'href' => 'https://eitaa.com/share/url?url=' . $encUrl . '&text=' . $encTitle,
            'icon' => asset('assets/site/images/social/eitaa.png'),
            'title' => 'اشتراک در ایتا',
        ],
        [
            'name' => 'سروش',
            'href' => 'https://splus.ir/share/url?url=' . $encUrl . '&text=' . $encTitle,
            'icon' => asset('assets/site/images/social/Soroush.png'),
            'title' => 'اشتراک در سروش پلاس',
        ],
        [
            'name' => 'تلگرام',
            'href' => 'https://t.me/share/url?url=' . $encUrl . '&text=' . $encTitle,
            'icon' => asset('assets/site/images/social/telegram-icon.png'),
            'title' => 'اشتراک در تلگرام',
        ],
        [
            'name' => 'روبیکا',
            'href' => 'https://web.rubika.ir/',
            'icon' => asset('assets/site/images/social/robik.png'),
            'title' => 'اشتراک در روبیکا',
            'class' => 'js-rubika-share',
            'extra' => true,
        ],
        [
            'name' => 'واتساپ',
            'href' => 'https://api.whatsapp.com/send?text=' . $encMessage,
            'icon' => asset('assets/site/images/social/whatssap-icon.png'),
            'title' => 'اشتراک در واتساپ',
        ],
        [
            'name' => 'اینستاگرام',
            'href' => 'https://www.instagram.com/',
            'icon' => asset('assets/site/images/social/instagram-icon.png'),
            'title' => 'اشتراک در اینستاگرام',
            'class' => 'js-blog-native-share',
        ],
    ];
@endphp
<div class="modal fade blog-share"
     id="shareBlog"
     tabindex="-1"
     aria-labelledby="blogShareTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered blog-share__dialog">
        <div class="modal-content blog-share__content">
            <div class="blog-share__panel">
                <span class="blog-share__handle" aria-hidden="true"></span>
                <button type="button"
                        class="blog-share__close"
                        data-bs-dismiss="modal"
                        aria-label="بستن">
                    <i class="bi bi-x-lg" aria-hidden="true"></i>
                </button>

                <header class="blog-share__head">
                    <span class="blog-share__icon" aria-hidden="true">
                        <i class="bi bi-share"></i>
                    </span>
                    <div class="blog-share__intro">
                        <h2 id="blogShareTitle" class="blog-share__title">اشتراک‌گذاری</h2>
                        <p class="blog-share__hint">لینک را کپی کنید یا برای دوستان بفرستید</p>
                    </div>
                </header>

                <div class="blog-share__copy">
                    <label class="visually-hidden" for="blogShareUrl">لینک مطلب</label>
                    <input id="blogShareUrl"
                           class="blog-share__url"
                           type="text"
                           value="{{ $shareUrl }}"
                           readonly
                           dir="ltr"
                           spellcheck="false">
                    <button type="button"
                            class="blog-share__copy-btn js-blog-copy-link"
                            data-share-url="{{ e($shareUrl) }}"
                            aria-label="کپی لینک">
                        <i class="bi bi-clipboard" aria-hidden="true"></i>
                        <i class="bi bi-check2" aria-hidden="true"></i>
                        <span class="blog-share__copy-label">کپی لینک</span>
                    </button>
                </div>

                <button type="button"
                        class="blog-share__native js-blog-native-share"
                        hidden
                        data-share-url="{{ e($shareUrl) }}"
                        data-share-title="{{ e($shareTitle) }}"
                        data-share-text="{{ e($shareTextOneLine) }}">
                    <i class="bi bi-box-arrow-up" aria-hidden="true"></i>
                    ارسال از این دستگاه
                </button>

                <ul class="blog-share__apps">
                    @foreach($shareApps as $app)
                        <li>
                            <a href="{{ $app['href'] }}"
                               class="blog-share__app {{ $app['class'] ?? '' }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               title="{{ $app['title'] }}"
                               @if(!empty($app['extra']))
                                   data-rubika-intent="{{ e($rubikaIntentHref) }}"
                                   data-share-url="{{ e($shareUrl) }}"
                                   data-share-title="{{ e($shareTitle) }}"
                                   data-share-text="{{ e($shareTextOneLine) }}"
                                   data-fallback-web="https://web.rubika.ir/"
                               @endif
                               @if(($app['class'] ?? '') === 'js-blog-native-share')
                                   data-share-url="{{ e($shareUrl) }}"
                                   data-share-title="{{ e($shareTitle) }}"
                                   data-share-text="{{ e($shareTextOneLine) }}"
                               @endif>
                                <span class="blog-share__app-icon">
                                    <img src="{{ $app['icon'] }}" alt="" width="40" height="40">
                                </span>
                                <span class="blog-share__app-name">{{ $app['name'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
<script>
(function () {
    var nativeBtn = document.querySelector('#shareBlog .blog-share__native');
    if (nativeBtn && navigator.share) {
        nativeBtn.hidden = false;
    }

    document.addEventListener('click', function (e) {
        var copyBtn = e.target.closest('.js-blog-copy-link');
        if (copyBtn) {
            var url = copyBtn.getAttribute('data-share-url') || '';
            var label = copyBtn.querySelector('.blog-share__copy-label');
            function markCopied() {
                copyBtn.classList.add('is-copied');
                copyBtn.setAttribute('aria-label', 'لینک کپی شد');
                if (label) label.textContent = 'کپی شد';
                window.setTimeout(function () {
                    copyBtn.classList.remove('is-copied');
                    copyBtn.setAttribute('aria-label', 'کپی لینک');
                    if (label) label.textContent = 'کپی لینک';
                }, 1800);
            }
            function fallbackCopy() {
                var input = document.getElementById('blogShareUrl');
                if (!input) return;
                input.focus();
                input.select();
                try {
                    document.execCommand('copy');
                    markCopied();
                } catch (err) {}
            }
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url).then(markCopied).catch(fallbackCopy);
            } else {
                fallbackCopy();
            }
            return;
        }

        var rubika = e.target.closest('a.js-rubika-share');
        if (rubika) {
            var u = rubika.getAttribute('data-share-url') || '';
            var t = rubika.getAttribute('data-share-title') || '';
            var x = rubika.getAttribute('data-share-text') || u;
            var web = rubika.getAttribute('data-fallback-web');
            var intent = rubika.getAttribute('data-rubika-intent');
            var isAndroid = /Android/i.test(navigator.userAgent);
            function openWeb() {
                if (web) window.open(web, '_blank', 'noopener,noreferrer');
            }
            function tryAndroidIntent() {
                if (intent && isAndroid) window.location.href = intent;
                else openWeb();
            }
            if (navigator.share) {
                e.preventDefault();
                navigator.share({ title: t, text: x, url: u }).catch(function (err) {
                    if (err && err.name === 'AbortError') return;
                    tryAndroidIntent();
                });
                return;
            }
            if (isAndroid && intent) {
                e.preventDefault();
                window.location.href = intent;
                return;
            }
            return;
        }

        var a = e.target.closest('.js-blog-native-share');
        if (!a || !window.navigator.share) return;
        e.preventDefault();
        var shareUrl = a.getAttribute('data-share-url') || '';
        var title = a.getAttribute('data-share-title') || '';
        var text = a.getAttribute('data-share-text') || shareUrl;
        var fallback = a.getAttribute('href');
        navigator.share({ title: title, text: text, url: shareUrl }).catch(function (err) {
            if (err && err.name === 'AbortError') return;
            if (fallback) window.open(fallback, '_blank', 'noopener,noreferrer');
        });
    }, false);
})();
</script>
