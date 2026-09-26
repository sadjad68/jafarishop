                <div class="row w-100 m-0 mt-4 px-0">
                    <div class="admin-section-title">
                        <span class="admin-section-ico"><i class="bi bi-stars"></i></span>
                        <span class="admin-section-label">فیچرهای فعلی سایت</span>
                        <hr>
                    </div>
                    <div class="col-12 p-md-2 p-1">
                        <div class="admin-features-card">
                            <div class="admin-features-grid">

                                <section class="admin-features-group">
                                    <h3 class="admin-features-group-head">
                                        <span class="admin-update-ico"><i class="bi bi-box-seam"></i></span>
                                        فروشگاه و محصول
                                    </h3>
                                    <ul class="admin-features-list">
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>محصولات و دسته‌بندی</strong> — مدیریت کامل فروشگاه، نمایش یا مخفی‌کردن هر دسته در سایت</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>محصولات چندمتغیره</strong> — متغیرهای وابسته و مرحله‌ای برای انتخاب پیچیده</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>برند، مشخصات و فیلتر</strong> — فیلتر محصولات در دسکتاپ و موبایل</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>تخفیف و شعار</strong> — کد تخفیف، حراج و شعارهای محصول</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span>
                                                <strong>موجود شد خبرم کن</strong> — اطلاع‌رسانی موجودی (خطوط خدماتی و اختصاصی)
                                                <a href="{{ Config::get('video_configs.videos.product_notification') }}" target="_blank" rel="noopener noreferrer" class="admin-features-link">ویدئو راهنما</a>
                                            </span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span>
                                                <strong>حراج شد خبرم کن</strong> — اطلاع‌رسانی کاهش قیمت (خطوط خدماتی و اختصاصی)
                                                <a href="{{ Config::get('video_configs.videos.product_notification') }}" target="_blank" rel="noopener noreferrer" class="admin-features-link">ویدئو راهنما</a>
                                            </span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>اشتراک‌گذاری محصول</strong> — دکمه شبکه اجتماعی، شناور یا چسبیده به صفحه</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>ویدیو و سوالات متداول</strong> — برای هر محصول، به‌همراه محصولات مرتبط و مکمل</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>موجودی و قیمت</strong> — ناموجود، تماس بگیرید، و آپدیت گروهی از اکسل</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>سازنده محصول</strong> — ثبت و جستجوی ادمین ایجادکننده در پنل</span>
                                        </li>
                                    </ul>
                                </section>

                                <section class="admin-features-group">
                                    <h3 class="admin-features-group-head">
                                        <span class="admin-update-ico"><i class="bi bi-bag-check"></i></span>
                                        سفارش و پرداخت
                                    </h3>
                                    <ul class="admin-features-list">
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>سبد خرید و سفارش</strong> — مدیریت سفارش‌ها و سبدهای رهاشده</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>پرداخت کارت به کارت</strong> — نمایش شماره کارت و شبا، آپلود فیش و تأیید ادمین</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>درگاه‌های آنلاین</strong> — زرین‌پال، سپ، صادرات، پارسیان، زیبال، ایران‌درگاه، دیجی‌پی، آقای پرداخت، اسنپ‌پی و سداد</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>تعرفه درگاه</strong> — درصد کارمزد روی مبلغ ارسالی به درگاه</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>کد پیگیری پرداخت</strong> — نمایش در جزئیات سفارش (زرین‌پال و سپ)</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>ترک سفارش ترب</strong> — ارسال خریدهای آمده از ترب از مسیر <code>torob/v1/orders</code></span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>چاپ لیبل پستی</strong> — برچسب ارسال مستقیم از پنل سفارش</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>ارسال و آدرس</strong> — روش ارسال، وضعیت سفارش، استان، شهر و آدرس‌ها</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>تیک پذیرش قوانین</strong> — صفحه قوانین و الزام تأیید قبل از پرداخت</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>هشدار مرحله پرداخت</strong> — متن قابل ویرایش قبل از دکمه پرداخت (مثلاً خاموش کردن VPN)</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>خروجی اکسل سفارش</strong> — انتخاب ستون‌های دلخواه برای خروجی</span>
                                        </li>
                                    </ul>
                                </section>

                                <section class="admin-features-group">
                                    <h3 class="admin-features-group-head">
                                        <span class="admin-update-ico"><i class="bi bi-palette"></i></span>
                                        قالب و ظاهر سایت
                                    </h3>
                                    <ul class="admin-features-list">
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>تم لومیرا</strong> — قالب خدماتی و فروشگاهی با گالری، پکیج، تیم و گواهی‌نامه</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>تم مارکتو</strong> — قالب فروشگاهی بنرمحور، نوار اطلاع‌رسانی، برند و بلاگ در صفحه اول</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>تم‌های رنگی</strong> — پالت‌های اختصاصی قابل انتخاب از تنظیمات</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>اسلایدر و بنر</strong> — اسلایدر دسکتاپ/موبایل و بنرهای میانی (مارکتو)</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>جایگاه H1 اسلایدر</strong> — بالا یا پایین اسلایدر در تم مارکتو</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>منو</strong> — مگا‌منو یا دراپ‌داون، آیکون‌ها، و جایگزینی سبد با تماس در منوی موبایل</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>فوتر هوشمند</strong> — لینک‌های دسترسی سریع؛ ساعات کاری در صورت فعال بودن خودکار اضافه می‌شود</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>جستجوی سایت</strong> — جستجوی زنده محصولات و محتوا در هدر</span>
                                        </li>
                                    </ul>
                                </section>

                                <section class="admin-features-group">
                                    <h3 class="admin-features-group-head">
                                        <span class="admin-update-ico"><i class="bi bi-journal-richtext"></i></span>
                                        محتوا، سئو و ارتباط
                                    </h3>
                                    <ul class="admin-features-list">
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>مطالب و دسته‌بندی</strong> — وبلاگ با مطالب مرتبط در صفحه جزئیات</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>صفحات ایستا</strong> — درباره ما، قوانین و صفحات سفارشی</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>نظرات و تماس با ما</strong> — مدیریت دیدگاه‌ها و پیام‌های دریافتی</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>تگ‌ها و سایت‌مپ</strong> — برچسب محتوا و نقشه سایت</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>سئو</strong> — سئو صفحات، ریدایرکت و کنونیکال</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>گواهی‌نامه و افتخارات</strong> — نمایش در صفحه اول تم لومیرا</span>
                                        </li>
                                    </ul>
                                </section>

                                <section class="admin-features-group">
                                    <h3 class="admin-features-group-head">
                                        <span class="admin-update-ico"><i class="bi bi-building"></i></span>
                                        بخش شرکتی
                                    </h3>
                                    <ul class="admin-features-list">
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>گالری</strong> — دسته‌بندی تصاویر و نمایش گالری در سایت</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>خدمات</strong> — معرفی خدمات و دریافت درخواست از سایت</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>نمونه کارها</strong> — پرتفولیو با مقایسه تصویر قبل و بعد</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>نرخ‌ها و پکیج‌ها</strong> — جدول قیمت و بسته‌های خدماتی</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>شعب و شبکه‌های اجتماعی</strong> — چند شعبه و لینک اینستاگرام، بله و بقیه</span>
                                        </li>
                                    </ul>
                                </section>

                                <section class="admin-features-group">
                                    <h3 class="admin-features-group-head">
                                        <span class="admin-update-ico"><i class="bi bi-people"></i></span>
                                        کاربران و پیام‌رسانی
                                    </h3>
                                    <ul class="admin-features-list">
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>ورود با پیامک</strong> — ارسال کد تأیید از کاوه‌نگار</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>کد تأیید در بله</strong> — ارسال OTP از پیام‌رسان بله در کنار پیامک</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>پنل کاربری</strong> — سفارش‌ها، آدرس‌ها و ادامه سبد خرید از حساب مشتری</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>کاربران، مدیران و پرسنل</strong> — سطح دسترسی جداگانه برای هر نقش</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>پیامک ادمین</strong> — چند شماره برای اطلاع سفارش جدید و تغییر وضعیت</span>
                                        </li>
                                    </ul>
                                </section>

                                <section class="admin-features-group">
                                    <h3 class="admin-features-group-head">
                                        <span class="admin-update-ico"><i class="bi bi-gear"></i></span>
                                        پنل مدیریت
                                    </h3>
                                    <ul class="admin-features-list">
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>داشبورد فروش</strong> — نمودار پرداخت‌شده و پرداخت‌نشده با بازه‌های زمانی</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>تم روشن و تاریک</strong> — ظاهر شیشه‌ای پنل با ذخیره ترجیح ادمین</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span>
                                                <strong>مستندات API</strong> — راهنمای اتصال سیستم‌های دیگر
                                                <a href="{{ route('admin.api.information') }}" class="admin-features-link">مشاهده مستندات</a>
                                            </span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>API مدیریت</strong> — دسترسی JWT برای محصولات، دسته‌ها، برند و سفارش‌ها</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span><strong>تنظیمات عمومی</strong> — لوگو، Favicon، منو، فوتر، پیامک و صفحه اول از یکجا</span>
                                        </li>
                                    </ul>
                                </section>

                            </div>
                        </div>
                    </div>
                </div>
