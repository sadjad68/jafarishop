<?php
return [
    'admin' => 'admin',
    'panel' => 'panel',
    'permissions' => [

        'dashboard' => [
            'title' => 'داشبورد پنل',
        ],

        'admin' => array(
            'title' => 'مدیران سایت',
            'access' => array(
                'index' => 'مشاهده مدیران',
                'create' => 'اضافه کردن مدیر ',
                'edit' => ' ویرایش مدیر ',
                'delete' => 'حذف مدیر ',
            ),
        ),
        'common' => array(
            'title' => 'مشترکات',
            'access' => array(
                'delete-image' => 'حذف تصاویر چندتایی',
                'remove-image' => 'حذف تصاویر',
                'set-thumb' => 'تصویر شاخص',
                'sort-image' => 'ترتیب تصاویر',
                'logs' => 'لاگ ها',
            ),
        ),

        'user' => array(
            'title' => 'کاربران',
            'access' => array(
                'index' => 'مشاهده کاربر',
                'create' => 'اضافه کردن کاربر',
                'edit' => ' ویرایش کاربر',
                'delete' => 'حذف کاربر',
            ),
        ),
        'basket' => array(
            'title' => 'سبد خرید کاربران',
            'access' => array(
                'index' => 'مشاهده سبد ها',
                'detail' => 'مشاهده جزئیات',
            ),
        ),
        'setting' => array(
            'title' => 'تنظیمات',
            'access' => array(
                'index' => 'مشاهده ',
                'edit' => 'ویرایش ',
                'clear-cache' => 'پاک کردن کش سایت ',
            ),
        ),
        'setting-partial' => array(
            'title' => 'تنظیمات برنامه نویس',
            'access' => array(
                'index' => 'مشاهده ',
                'create' => 'اضافه کردن ',
                'edit' => ' ویرایش ',
                'delete' => 'حذف ',
            ),
        ),
        'theme' => array(
            'title' => 'منو و تم',
            'access' => array(
                'index' => 'مشاهده ',
                'edit' => 'ویرایش ',
            ),
        ),
        'comment' => array(
            'title' => 'نظرات',
            'access' => array(
                'index' => 'مشاهده ',
                'edit' => 'ویرایش ',
                'delete' => 'حذف ',
            ),
        ),
        'contact' => array(
            'title' => 'تماس با ما',
            'access' => array(
                'index' => 'مشاهده ',
                'status' => 'بررسی شد ',
                'delete' => 'حذف ',
            ),
        ),
        'permission' => array(
            'title' => 'دسترسی',
            'access' => array(
                'index' => 'مشاهده دسترسی',
                'add' => 'اضافه دسترسی',
                'edit' => 'ویرایش دسترسی',
                'delete' => 'حذف دسترسی',
            ),
        ),
        'api' => array(
            'title' => 'دسترسی به اطلاعات api',
            'access' => array(
                'information' => 'مشاهده داکیومنت',
            ),
        ),
        'service' => array(
            'title' => 'خدمات',
            'access' => array(
                'index' => 'مشاهده خدمات',
                'create' => 'اضافه کردن خدمات',
                'edit' => ' ویرایش خدمات',
                'delete' => 'حذف خدمات',
                'delete-root' => 'حذف کامل خدمات',
                'sort' => 'ترتیب ',

            ),
        ),
        'seo' => array(
            'title' => 'سئو متاها',
            'access' => array(
                'index' => 'مشاهده ',
                'create' => 'اضافه کردن ',
                'create' => 'اضافه کردن ',
                'edit' => ' ویرایش ',
                'index-all' => ' ایندکس همگانی ',
            ),
        ),
        'redirect' => array(
            'title' => 'ریدایرکت ها',
            'access' => array(
                'index' => 'مشاهده ',
                'create' => 'اضافه کردن ',
                'delete' => 'حذف ',
            ),
        ),
        'canonical' => array(
            'title' => 'کنونیکال',
            'access' => array(
                'index' => 'مشاهده کنونیکال',
                'create' => 'اضافه کردن کنونیکال',
                'edit' => ' ویرایش کنونیکال',
                'delete' => 'حذف کنونیکال',
            ),
        ),
        'blog' => array(
            'title' => 'مطالب',
            'access' => array(
                'index' => 'مشاهده مطالب',
                'create' => 'اضافه کردن مطالب',
                'edit' => ' ویرایش مطالب',
                'delete' => 'حذف مطالب',
            ),
        ),
        'blog-category' => array(
            'title' => 'دسته بندی مطلب',
            'access' => array(
                'index' => 'مشاهده دسته بندی مطلب',
                'create' => 'اضافه کردن دسته بندی مطلب',
                'edit' => ' ویرایش دسته بندی مطلب',
                'delete' => 'حذف دسته بندی مطلب',
            ),
        ),
        'video' => array(
            'title' => 'ویدیوها',
            'access' => array(
                'index' => 'مشاهده ویدیوها',
                'create' => 'اضافه کردن ویدیو',
                'edit' => 'ویرایش ویدیو',
                'delete' => 'حذف ویدیو',
            ),
        ),
        'gallery-category' => array(
            'title' => 'دسته بندی تصاویر',
            'access' => array(
                'index' => 'مشاهده ',
                'create' => 'اضافه کردن ',
                'edit' => ' ویرایش ',
                'delete' => 'حذف ',
            ),
        ),
        'gallery' => array(
            'title' => 'تصاویر',
            'access' => array(
                'index' => 'مشاهده ',
                'create' => 'اضافه کردن ',
                'edit' => ' ویرایش ',
                'delete' => 'حذف ',
            ),
        ),
        'worksample' => array(
            'title' => 'نمونه کارها',
            'access' => array(
                'index' => 'مشاهده ',
                'create' => 'اضافه کردن ',
                'edit' => ' ویرایش ',
                'delete' => 'حذف ',
                'image' => 'تصاویر ',
                'thumbnail' => 'انتخاب تصویر مشخصه ',
                'deleteImage' => 'حذف تصویر ',
                'createImage' => 'اضافه کردن تصویر ',
            ),
        ),
        'fee' => array(
            'title' => 'نرخ ها',
            'access' => array(
                'index' => 'مشاهده ',
                'create' => 'اضافه کردن ',
                'edit' => ' ویرایش ',
                'delete' => 'حذف ',
            ),
        ),
        'service-request' => array(
            'title' => 'نرخ ها',
            'access' => array(
                'index' => 'مشاهده ',
                'show' => ' نمایش ',
                'delete' => 'حذف ',
            ),
        ),
        'certificate' => array(
            'title' => 'گواهینامه ها',
            'access' => array(
                'index' => 'مشاهده ',
                'create' => 'اضافه کردن ',
                'edit' => ' ویرایش ',
                'delete' => 'حذف ',
            ),
        ),
        'package' => array(
            'title' => 'پکیج ها',
            'access' => array(
                'index' => 'مشاهده ',
                'create' => 'اضافه کردن ',
                'edit' => ' ویرایش ',
                'delete' => 'حذف ',
            ),
        ),
        'banner' => array(
            'title' => 'اسلایدر ها',
            'access' => array(
                'index' => 'مشاهده ',
                'create' => 'اضافه کردن ',
                'edit' => ' ویرایش ',
                'delete' => 'حذف ',
                'sort' => 'ترتیب نمایش',
            ),
        ),
        'highlight' => array(
            'title' => 'بنر ها',
            'access' => array(
                'index' => 'مشاهده ',
                'create' => 'اضافه کردن ',
                'edit' => ' ویرایش ',
                'delete' => 'حذف ',
            ),
        ),
        'branch' => array(
            'title' => 'شعب',
            'access' => array(
                'index' => 'مشاهده ',
                'create' => 'اضافه کردن ',
                'edit' => ' ویرایش ',
                'delete' => 'حذف ',
            ),
        ),
        'social' => array(
            'title' => 'شبکه های اجتماعی',
            'access' => array(
                'index' => 'مشاهده ',
                'create' => 'اضافه کردن ',
                'edit' => ' ویرایش ',
                'delete' => 'حذف ',
            ),
        ),
        'course' => array(
            'title' => 'دوره ها',
            'access' => array(
                'index' => 'مشاهده دوره ها',
                'create' => 'اضافه کردن دوره ها',
                'edit' => ' ویرایش دوره ها',
                'delete' => 'حذف دوره ها',
            ),
        ),
        'course-category' => array(
            'title' => 'دسته بندی دوره ها',
            'access' => array(
                'index' => 'مشاهده ',
                'create' => 'اضافه کردن ',
                'edit' => ' ویرایش ',
                'delete' => 'حذف ',
            ),
        ),
        'session' => array(
            'title' => 'جلسات',
            'access' => array(
                'index' => 'مشاهده ',
                'create' => 'اضافه کردن ',
                'edit' => ' ویرایش ',
                'delete' => 'حذف ',
                'order' => 'مرتبط سازی',
            ),
        ),
        'session-file' => array(
            'title' => 'فایل جلسات',
            'access' => array(
                'delete' => 'حذف ',
            ),
        ),
        'product-category' => array(
            'title' => 'دسته بندی محصول',
            'access' => array(
                'index' => 'مشاهده دسته بندی محصول',
                'create' => 'اضافه کردن دسته بندی محصول',
                'edit' => ' ویرایش دسته بندی محصول',
                'delete' => 'حذف دسته بندی محصول',
                'delete-root' => 'حذف کامل دسته بندی محصول',
                'sort' => 'ترتیب دسته بندی',
            ),
        ),
        'product' => array(
            'title' => 'محصولات',
            'access' => array(
                'index' => 'مشاهده محصولات',
                'create' => 'اضافه کردن محصولات',
                'edit' => ' ویرایش محصولات',
                'delete' => 'حذف محصولات',
                'timer' => 'تایمر',
                'sort' => 'ترتیب محصولات',
            ),
        ),
        'product.import' => array(
            'title' => 'ورود گروهی محصولات',
            'access' => array(
                'index' => 'مشاهده صفحه ورود گروهی',
                'store' => 'ثبت / آپلود فایل',
                'sample' => 'دانلود فایل نمونه',
            ),
        ),
        'brand' => array(
            'title' => 'برند محصول',
            'access' => array(
                'index' => 'مشاهده برند محصول',
                'create' => 'اضافه کردن برند محصول',
                'edit' => ' ویرایش برند محصول',
                'delete' => 'حذف برند محصول',
            ),
        ),
        'tag' => array(
            'title' => 'تگ',
            'access' => array(
                'index' => 'مشاهده تگ',
                'create' => 'اضافه کردن تگ',
                'edit' => ' ویرایش تگ',
                'delete' => 'حذف تگ',
                'sort' => 'ترتیب ',
            ),
        ),
        'specification' => array(
            'title' => 'مشخصه',
            'access' => array(
                'index' => 'مشاهده مشخصه',
                'create' => 'اضافه کردن مشخصه',
                'edit' => ' ویرایش مشخصه',
                'delete' => 'حذف مشخصه',
            ),
        ),
        'specification-value' => array(
            'title' => 'مقادیر',
            'access' => array(
                'index' => 'مشاهده مقادیر',
                'create' => 'اضافه کردن مقادیر',
                'edit' => ' ویرایش مقادیر',
                'delete' => 'حذف مقادیر',
            ),
        ),
        'slogan' => array(
            'title' => 'شعار ها',
            'access' => array(
                'index' => 'مشاهده شعار',
                'create' => 'اضافه کردن شعار',
                'edit' => ' ویرایش شعار',
                'delete' => 'حذف شعار',
            ),
        ),
        'page' => array(
            'title' => 'صفحات',
            'access' => array(
                'index' => 'مشاهده صفحه',
                'create' => 'اضافه کردن صفحه',
                'edit' => ' ویرایش صفحه',
                'delete' => 'حذف صفحه',
            ),
        ),

        'availability_notification' => array(
            'title' => 'اطلاع‌رسانی موجودی',
            'access' => array(
                'index' => 'مشاهده درخواست‌ها',
                'bulk-send' => 'ارسال پیامک گروهی',
                'export' => 'خروجی',
                'delete' => 'حذف',
            ),
        ),
        'sale_notification' => array(
            'title' => 'اطلاع‌رسانی حراج',
            'access' => array(
                'index' => 'مشاهده درخواست‌ها',
                'bulk-send' => 'ارسال پیامک گروهی',
                'export' => 'خروجی',
                'delete' => 'حذف',
            ),
        ),
        'sitemap' => array(
            'title' => 'سایت مپ',
            'access' => array(
                'index' => 'مشاهده سایت مپ',
                'create' => 'اضافه کردن سایت مپ',
                'edit' => ' ویرایش سایت مپ',
                'delete' => 'حذف سایت مپ',
            ),
        ),
        'product-video-faq' => array(
            'title' => 'سوالات متداول و ویدیوی محصول',
            'access' => array(
                'index' => 'مشاهده ',
                'create' => 'اضافه کردن ',
                'edit' => ' ویرایش ',
                'delete-video' => 'حذف ویدیو ',
                'delete-faq' => 'حذف سوال ',
            ),
        ),
        'product-image' => array(
            'title' => 'تصاویر محصول',
            'access' => array(
                'index' => 'مشاهده ',
                'create' => 'اضافه کردن ',
                'edit' => ' ویرایش ',
            ),
        ),
        'product-variant' => array(
            'title' => 'متغیرهای محصول',
            'access' => array(
                'index' => 'مشاهده ',
                'create' => 'اضافه کردن ',
                'create-main' => 'اضافه کردن متغییر اصلی ',
                'edit' => ' ویرایش ',
                'delete' => ' حذف ',
            ),
        ),
        'product-property-spf-tag' => array(
            'title' => 'اختصاص مشخصه و تگ و ویژگی به محصول',
            'access' => array(
                'index' => 'مشاهده ',
                'create' => 'اضافه کردن ',
                'edit' => ' ویرایش ',
                'delete-prop' => 'حذف ویژگی ',

            ),
        ),
        'order' => array(
            'title' => 'سفارش',
            'access' => array(
                'index' => 'مشاهده ',
                'factor' => ' فاکتور ',
                'detail' => ' جزییات ',
                'order-return' => ' مرجوع کل فاکتور ',
                'return' => ' مرجوع آیتم ',
                'change-shipping-status' => ' تغییر وضعیت ',
                'bijak' => 'آپلود تصویر بیجک',
                'delete' => 'حذف ',
            ),
        ),
        'bank' => array(
            'title' => 'درگاه های بانکی',
            'access' => array(
                'index' => 'مشاهده ',
                'edit' => ' ویرایش ',
            ),
        ),
        'order-shipping-status' => array(
            'title' => ' وضعیت سفارش',
            'access' => array(
                'index' => 'مشاهده ',
                'create' => 'اضافه کردن ',
                'edit' => ' ویرایش ',
                'delete' => 'حذف ',
            ),
        ),
        'shipping-method' => array(
            'title' => 'روش ارسال',
            'access' => array(
                'index' => 'مشاهده ',
                'create' => 'اضافه کردن ',
                'edit' => ' ویرایش ',
                'delete' => 'حذف ',
            ),
        ),
        'discount' => array(
            'title' => 'کد تخفیف',
            'access' => array(
                'index' => 'مشاهده ',
                'create' => 'اضافه کردن ',
                'edit' => ' ویرایش ',
                'delete' => 'حذف ',
            ),
        ),
        'state' => array(
            'title' => 'استان',
            'access' => array(
                'index' => 'مشاهده ',
                'change-status' => 'تغییر وضعیت ',

            ),
        ),
        'city' => array(
            'title' => 'شهر',
            'access' => array(
                'index' => 'مشاهده ',
                'change-status' => 'تغییر وضعیت ',
            ),
        ),
        'address' => array(
            'title' => 'آدرس',
            'access' => array(
                'index' => 'مشاهده ',
            ),
        ),
    ],
    'api_permissions' => [

        'auth.refresh' => 'free',
        'auth.logout' => 'free',
        'auth.me' => 'free',

        'product-category.index' => 'admin.product-category.index',
        'product-category.store' => 'admin.product-category.create',
        'product-category.update' => 'admin.product-category.edit',
        'product-category.show' => 'admin.product-category.index',

        'product.index' => 'admin.product.index',
        'product.store' => 'admin.product.create',
        'product.update' => 'admin.product.edit',
        'product.show' => 'admin.product.index',
        'product.timer' => 'admin.product.timer',

        'product-image.create' => 'admin.product-image.create',
        'product-image.add-variants' => 'admin.product-image.create',
        'product-image.set-thumbnail' => 'admin.common.set-thumb',

        'product.specification-select' => 'admin.product-property-spf-tag.create',
        'product.specification-text' => 'admin.product-property-spf-tag.edit',

        'specification.index' => 'admin.specification.index',
        'specification.products' => 'admin.specification.index',
        'specification.text-index' => 'admin.specification.index',
        'specification.values' => 'admin.specification-value.index',

        'prerequisite.brands' => 'free',
        'prerequisite.tags' => 'free',
        'prerequisite.services' => 'free',

        'blog-category.index' => 'admin.blog-category.index',
        'blog-category.store' => 'admin.blog-category.create',
        'blog-category.update' => 'admin.blog-category.edit',
        'blog-category.show' => 'admin.blog-category.index',

        'blog.index' => 'admin.blog.index',
        'blog.store' => 'admin.blog.create',
        'blog.update' => 'admin.blog.edit',
        'blog.show' => 'admin.blog.index',

        'user.index' => 'admin.user.index',
        'user.create' => 'admin.user.create',
        'user.edit' => 'admin.user.edit',
        'user.delete' => 'admin.user.delete',

    ],

    'user_types' => [
        'Teacher' => 'پرسنل',
        'Student' => 'دانشجو',
        'Admin' => 'ادمین',
    ],
    'price_formula' => [
        'TgjuSilver' => 'نقره از سایت tgu',
    ],
];
