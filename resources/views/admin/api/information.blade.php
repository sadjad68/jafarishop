<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مستندات API سیستم مدیریت محتوا (CMS)</title>
    <style>

        @font-face {
        font-family: 'iransans';
        font-style: normal;
        font-weight: normal;
        src: url('{{ asset('assets/admin/fonts/IRANSansWeb.eot') }}');
        src:
            url('{{ asset('assets/admin/fonts/IRANSansWeb.eot?#iefix') }}') format('embedded-opentype'),
            url('{{ asset('assets/admin/fonts/IRANSansWeb.woff2') }}') format('woff2'),
            url('{{ asset('assets/admin/fonts/IRANSansWeb.woff') }}') format('woff'),
            url('{{ asset('assets/admin/fonts/IRANSansWeb.ttf') }}') format('truetype'),
            url('{{ asset('assets/admin/fonts/IRANSansWeb.svg#IRANSansWeb') }}') format('svg');
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: 'iransans' !important;
        background: #f4f6f9;
        color: #333;
        line-height: 1.7;
        direction: rtl;
        text-align: right;
    }
        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            background: linear-gradient(135deg, #4a90e2, #357abd);
            color: white;
            padding: 40px 20px;
            text-align: center;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        nav {
            background: white;
            padding: 15px 0;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 100;
            margin-bottom: 30px;
        }
        nav ul {
            list-style: none;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        nav a {
            color: #4a90e2;
            text-decoration: none;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.3s;
        }
        nav a:hover {
            background: #e6f0ff;
            color: #2d6bc4;
        }
        section {
            background: white;
            margin-bottom: 30px;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }
        h2 {
            color: #4a90e2;
            border-bottom: 3px solid #e6f0ff;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-size: 1.8rem;
        }
        h3 {
            color: #357abd;
            margin: 25px 0 15px;
            font-size: 1.4rem;
            display: flex;
            align-items: center;
        }
        h3 span.method {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 0.8rem;
            margin-left: 10px;
            color: white;
        }
        .get { background: #28a745; }
        .post { background: #007bff; }
        .put { background: #ffc107; color: #212529; }
        .delete { background: #dc3545; }
        .endpoint {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            border-right: 5px solid #4a90e2;
            font-family: 'Courier New', monospace;
            font-size: 1rem;
        }
        .params, .body-example, .response-example {
            margin: 15px 0;
        }
        .params table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .params th, .params td {
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: right;
        }
        .params th {
            background: #e9ecef;
            color: #495057;
        }
        pre {
            background: #2d3748;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 8px;
            overflow-x: auto;
            font-size: 0.9rem;
            margin-top: 8px;
            direction: ltr;
            text-align: left;
        }
        code {
            font-family: 'Courier New', monospace;
            background: #e9ecef;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.9em;
        }
        .note {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 12px;
            border-radius: 8px;
            margin: 15px 0;
            font-size: 0.95rem;
        }
        .warning {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 12px;
            border-radius: 8px;
            margin: 15px 0;
        }
        footer {
            text-align: center;
            padding: 30px;
            color: #6c757d;
            font-size: 0.9rem;
            margin-top: 50px;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: bold;
            margin-left: 8px;
        }
        .required { background: #dc3545; color: white; }
        .optional { background: #6c757d; color: white; }
        @media (max-width: 768px) {
            nav ul { flex-direction: column; align-items: center; }
            header h1 { font-size: 2rem; }
            .container { padding: 15px; }
        }
        pre {
            background: #1e1e1e;
            color: #dcdcdc;
            padding: 1rem;
            border-radius: 8px;
            overflow-x: auto;
        }
        .comment {
            color: #9fd089;
            font-weight: bold;
        }
        /* دکمه دانلود Postman - بزرگ و خوشگل */
.download-section {
    background: linear-gradient(135deg, #1e3c72, #2a5298);
    padding: 40px 20px;
    text-align: center;
    border-radius: 16px;
    margin-top: 30px;
    box-shadow: 0 10px 30px rgba(30, 60, 114, 0.3);
    color: white;
    transition: transform 0.3s ease;
}
.download-section:hover {
    transform: translateY(-3px);
}
.download-section h2 {
    font-size: 2rem;
    margin-bottom: 12px;
    color: #fff;
}
.download-section p {
    font-size: 1.1rem;
    margin-bottom: 25px;
    opacity: 0.95;
}
.download-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #ffffff, #f8f9fa);
    color: #1e3c72;
    padding: 18px 38px;
    font-size: 1.3rem;
    font-weight: bold;
    text-decoration: none;
    border-radius: 50px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
    border: 2px solid rgba(255,255,255,0.3);
    min-width: 280px;
}
.download-btn:hover {
    transform: translateY(-4px);
    box-shadow: 0 14px 35px rgba(0,0,0,0.3);
    background: linear-gradient(135deg, #f8f9fa, #ffffff);
    color: #0d1b3a;
}
.download-btn svg {
    margin-left: 12px;
    transition: transform 0.3s ease;
}
.download-btn:hover svg {
    transform: scale(1.2);
}
@media (max-width: 768px) {
    .download-btn {
        font-size: 1.1rem;
        padding: 15px 30px;
        min-width: 240px;
    }
    .download-section h2 { font-size: 1.7rem; }
}
    </style>
</head>
<body>
    <div class="container">

        <!-- Header -->
        <header>
            <h1>مستندات API سیستم مدیریت محتوا (CMS)</h1>
            <p>راهنمای کامل استفاده از API برای مدیریت محصولات، دسته‌بندی‌ها، بلاگ و احراز هویت</p>
        </header>

        <!-- Navigation -->
        <nav>
            <ul>
                <li><a href="#introduction">مقدمه</a></li>
                <li><a href="#authentication">احراز هویت</a></li>
                <li><a href="#product-category">دسته‌بندی محصولات</a></li>
                <li><a href="#product">محصولات</a></li>
                <li><a href="#product-image">تصویر محصولات</a></li>
                <li><a href="#specification">مشخصات</a></li>
                <li><a href="#blog-category">دسته‌بندی بلاگ</a></li>
                <li><a href="#blog">بلاگ</a></li>
                <li><a href="#users">کاربران</a></li>
                <li><a href="#prerequisite">اطلاعات بیشتر</a></li>
            </ul>
        </nav>

        <!-- Introduction -->
        <section id="introduction">
            <h2>مقدمه</h2>
            <p>این مستندات شامل تمامی endpointهای API سیستم مدیریت محتوا (CMS) است. این API امکان مدیریت کامل محصولات، دسته‌بندی‌ها، بلاگ، مشخصات فنی و احراز هویت را فراهم می‌کند.</p>
            <div class="note">
                <strong>آدرس پایه (Base URL):</strong> <code>{{url('api/admin')}}</code> — باید در متغیرهای محیطی Postman یا برنامه تنظیم شود.
            </div>
            <p>تمام درخواست‌ها به جز ورود و رفرش توکن، نیاز به <strong>توکن Bearer</strong> در هدر دارند:</p>
            <pre>Authorization: Bearer @{{token}}</pre>
        </section>

        <!-- Authentication -->
        <section id="authentication">
            <h2>احراز هویت (Auth)</h2>

            <h3><span class="method post">POST</span> ورود به سیستم</h3>
            <div class="endpoint"> <code>{{url('api/admin')}}/login</code></div>
            <div class="note">
                از <strong>یوزرنیم</strong> و <strong>پسورد ادمین</strong> برای ورود استفاده کنید.
            </div>
            <div class="body-example">
                <strong>بدنه درخواست (form-data):</strong>
                <pre>{
  "email": "@{{email}}",
  "password": "@{{password}}"
}</pre>
            </div>
            <div class="note">
                پس از موفقیت، توکن در متغیر <code>token</code> ذخیره می‌شود.
            </div>

            <h3><span class="method post">POST</span> رفرش توکن</h3>
            <div class="endpoint"> <code>{{url('api/admin')}}/refresh</code></div>
            <p>برای تمدید توکن منقضی شده استفاده می‌شود.</p>

            <h3><span class="method post">POST</span> خروج از سیستم</h3>
            <div class="endpoint"> <code>{{url('api/admin')}}/logout</code></div>
            <p>توکن را از متغیرها حذف می‌کند.</p>

            <h3><span class="method get">GET</span> اطلاعات کاربر</h3>
            <div class="endpoint"><code>{{url('api/admin')}}/me</code></div>
            <p>اطلاعات پروفایل کاربر لاگین‌شده را برمی‌گرداند.</p>
        </section>

        <!-- Product Category -->
        <section id="product-category">
            <h2>دسته‌بندی محصولات</h2>

            <h3><span class="method get">GET</span> لیست دسته‌بندی‌ها</h3>
            <div class="endpoint"><code>{{url('api/admin')}}/product-category/</code></div>

            <h3><span class="method get">GET</span> نمایش دسته‌بندی</h3>
            <div class="endpoint"><code>{{url('api/admin')}}/product-category/show/{id}</code></div>
            <p>مثال: <code>/product-category/show/146</code></p>

            <h3><span class="method post">POST</span> افزودن دسته‌بندی</h3>
            <div class="endpoint"> <code>{{url('api/admin')}}/product-category/add</code></div>
            <p>فیلد <code>description</code> از HTML پشتیبانی می‌کند (مثل CKEditor). برای ارسال همراه با تصویر از <code>multipart/form-data</code> استفاده کنید.</p>
            <div class="body-example">
<pre>{
    "title": "ساعت مچی مردانه",
    "description": "&lt;p&gt;مجموعه‌ای از &lt;strong&gt;بهترین ساعت‌های مچی مردانه&lt;/strong&gt; با طراحی کلاسیک، اسپرت و هوشمند.&lt;/p&gt;&lt;ul&gt;&lt;li&gt;مناسب استفاده روزمره&lt;/li&gt;&lt;li&gt;گارانتی اصالت&lt;/li&gt;&lt;/ul&gt;",
    "show_in_first_page": true,
    "active": true,
    "parent_id": 42,
    "url": "saat-machi-mardane",
    "seo": {
        "title_seo": "خرید ساعت مچی مردانه اصل | جدیدترین مدل‌های ۱۴۰۴",
        "description_seo": "بهترین ساعت‌های مچی مردانه با گارانتی اصالت کالا. مدل‌های کلاسیک، اسپرت و هوشمند از برندهای معتبر با تخفیف ویژه.",
        "h1": "ساعت مچی مردانه",
        "noindex": false
    }
}</pre>
            </div>

            <h3><span class="method put">PUT</span> ویرایش دسته‌بندی</h3>
            <div class="endpoint"> <code>{{url('api/admin')}}/product-category/edit/{id}</code></div>

        </section>

        <!-- Product -->
        <section id="product">
            <h2>محصولات</h2>

            <h3><span class="method get">GET</span> لیست محصولات</h3>
            <div class="endpoint"><code>{{url('api/admin')}}/product/</code></div>
            <p>پارامترهای اختیاری: <code>page</code>, <code>title</code></p>

            <h3><span class="method get">GET</span> نمایش محصول</h3>
            <div class="endpoint"><code>{{url('api/admin')}}/product/show/{id}</code></div>
            <p>جایگزین <code>@{{product_simple_id}}</code></p>

            <h3><span class="method post">POST</span> افزودن محصول</h3>
            <div class="endpoint"> <code>{{url('api/admin')}}/product/add</code></div>
            <div class="body-example">
<pre>{
    "title": "زعفران سرگل ممتاز قائنات - ۱ گرم",
    <span class="comment">// عنوان محصول — باید شامل: نام + نوع + وزن + ویژگی کلیدی</span>
    <span class="comment">// مثال: "کفش نایک ایر جردن - سایز ۴۲"</span>

    "url": "zaferan-sargol-ghainat-1g",
    <span class="comment">// slug منحصر به فرد — فقط حروف کوچک، خط تیره، بدون فاصله</span>
    <span class="comment">// حتماً چک کن قبلاً استفاده نشده باشه</span>

    "price": 185000,
    <span class="comment">// قیمت اصلی (به تومان) — بدون تخفیف</span>

    "discounted_price": 159000,
    <span class="comment">// قیمت با تخفیف — باید کمتر از price باشه</span>
    <span class="comment">// اگر تخفیف نداره، حذف کن یا برابر price بذار</span>

    "description": "زعفران سرگل ۱۰۰٪ اصل قائنات با رنگ‌دهی بالا (۲۶+)، عطر قوی و طعم اصیل. بسته‌بندی بهداشتی در ظرف کریستالی با درب فلزی. مناسب برای آشپزی، دمنوش و هدیه.",
    <span class="comment">// توضیح کامل محصول — حداقل ۱۲۰ کاراکتر، شامل کلمات کلیدی</span>

    "active": true,
    <span class="comment">// نمایش در سایت؟ true = بله، false = مخفی (مثلاً در حال ویرایش)</span>

    "brand_id": 12,
    <span class="comment">// ID برند — از /prerequisite/brands/ بگیر</span>
    <span class="comment">// مثال: 12 = "زرین زعفران"</span>

    "weight": 1,
    <span class="comment">// وزن به گرم — برای محاسبه ارسال و موجودی</span>

    "related": [245, 248, 251],
    <span class="comment">// محصولات مرتبط — ID محصولات مشابه</span>
    <span class="comment">// نمایش در بخش "محصولات مشابه"</span>

    "complement": [189, 192],
    <span class="comment">// محصولات مکمل — مثلاً نبات، هاون، قوری</span>
    <span class="comment">// نمایش در "این محصول رو با این بخر"</span>

    "seo": {
      "title_seo": "خرید زعفران سرگل اصل قائنات ۱ گرم | قیمت روز + ارسال رایگان",
      <span class="comment">// عنوان صفحه در گوگل — حداکثر ۶۰ کاراکتر</span>
      <span class="comment">// شامل کلمه کلیدی + قیمت + مزیت</span>

      "description_seo": "زعفران سرگل ممتاز قائنات با گارانتی اصالت و آزمایشگاه معتبر. رنگ‌دهی بالا، عطر قوی و بسته‌بندی لوکس. مناسب آشپزی و هدیه. تحویل ۲۴ ساعته تهران.",
      <span class="comment">// توضیح در نتایج گوگل — حداکثر ۱۶۰ کاراکتر</span>

      "h1": "زعفران سرگل ممتاز قائنات - ۱ گرم",
      <span class="comment">// عنوان اصلی صفحه — بهتره با title یکسان باشه</span>

      "noindex": false
      <span class="comment">// false = ایندکس بشه | true = مخفی از گوگل (فقط برای تست)</span>
    },

    "main_variant_specification_id": 88,
    <span class="comment">// الزامی! ID مشخصه اصلی برای واریانت</span>
    <span class="comment">// مثلاً: 88 = "وزن" → بعدش می‌تونی ۱، ۵، ۱۰ گرم اضافه کنی</span>

    "stock": 150,
    <span class="comment">// موجودی انبار — عدد صحیح</span>

    "show_in_first_page": true,
    <span class="comment">// نمایش در صفحه اصلی؟ true = بله (فقط محصولات پرفروش)</span>

    "categories": [16, 18],
    <span class="comment">// ID دسته‌بندی‌ها — از /product-category/list بگیر</span>
    <span class="comment">// مثال: 16 = "زعفران"، 18 = "محصولات خوراکی"</span>

    "tags": [3, 5, 4, 27],
    <span class="comment">// ID تگ‌ها — از /prerequisite/tags بگیر</span>
    <span class="comment">// مثال: 27 = "زعفران قائنات"</span>

    "properties": [
      "زعفران سرگل ۱۰۰٪ اصل — بدون ناخالصی",
      "رنگ‌دهی فوق‌العاده (۲۶+ واحد) — غذاتو مثل خورشید طلایی می‌کنه!",
      "عطر و طعم اصیل قائنات — همون چیزی که مادربزرگت استفاده می‌کرد",
      "بسته‌بندی لوکس کریستالی با درب فلزی — هدیه‌ای شیک و ماندگار",
      "آزمایش‌شده در آزمایشگاه معتبر + گواهی اصالت کالا",
      "مناسب برای آشپزی، دمنوش، زعفران‌دمنوش و هدیه دادن"
    ]
    <span class="comment">// ویژگی‌های نمایش در صفحه محصول</span>
    <span class="comment">// هر خط = یک آیتم در لیست ویژگی‌ها</span>
  }
  </pre>
            </div>

            <h3><span class="method put">PUT</span> انتخاب مشخصات</h3>
            <div class="endpoint"> <code>{{url('api/admin')}}/product/specification/select/{id}/</code></div>
            <div class="body-example">
<pre>
[1827, 1828, 89]
<span class="comment">// شناسه مقدار های مشخصه ها </span>
</pre>
            </div>

            <h3><span class="method put">PUT</span> مشخصات متنی</h3>
            <div class="endpoint"> <code>{{url('api/admin')}}/product/specification/text/{id}/</code></div>
            <div class="body-example">
                <pre>{
  "2076": ["خوب", "عالی"],
  "29": ["خوب", "عالی"],
  <span class="comment">// شناسه مشخصه نوشتاری و مشخص کردن مقدار ها برای محصول </span>
}</pre>
            </div>
        </section>

        <!-- Product Image -->
        <section id="product-image">
            <h2>تصویر محصولات</h2>

            <!-- Create Product Images -->
            <h3><span class="method post">POST</span> افزودن تصویر محصول</h3>
            <div class="endpoint"><code>{{ url('') }}/product-image/create/{id}</code></div>
            <p>
                در این مسیر باید به‌جای
                <code>{id}</code>
                مقدار <strong>product_simple_id</strong> را ارسال کنید.
            </p>

            <h4>Body (Form-Data)</h4>
            <div class="body-example">
<pre>{
  "image": [
      "file1.jpg",
      "file2.webp",
      "file3.png"
  ]
}

<span class="comment">
// image[] → فایل‌های تصویر
// فرمت‌های مجاز: jpg, jpeg, png, webp
// تمام فایل‌ها باید در قالب multipart/form-data ارسال شوند.
</span>
</pre>
            </div>


            <!-- Add Image Variants -->
            <h3><span class="method post">POST</span> افزودن متغیر برای تصویر</h3>
            <div class="endpoint"><code>{{ url('') }}/product-image/add-variants/{id}</code></div>

            <p>
                در این مسیر به‌جای <code>{id}</code>
                مقدار <strong>image_simple_id</strong> وارد می‌شود (ID تصویر اصلی).
            </p>

            <p>
                هر تصویر می‌تواند چند متغیر داشته باشد.
                برای هر متغیر باید ID آن (variant_id) ارسال شود.
            </p>

            <h4>Body (JSON)</h4>
            <div class="body-example">
<pre>{
  "variants": {
      "10913": ["5814", "5813"]
  }
}

<span class="comment">
ساختار:
variants: {
    image_simple_id: [variant_value_ids]
}

مثال:
10913 → ID تصویر
["5814", "5813"] → این تصویر دو مقدار متغیر دارد
</span>
</pre>
            </div>
            <!-- Add Image Variants -->
            <h3><span class="method post">POST</span>تصویر مشخصه </h3>
            <div class="endpoint"><code>{{ url('') }}/product-image/set-thumbnail/{id}</code></div>
            <p>
                در این مسیر به‌جای <code>{id}</code>
                مقدار <strong>image_simple_id</strong> وارد می‌شود (ID تصویر اصلی).
            </p>
        </section>


        <!-- Specification -->
        <section id="specification">
            <h2>مشخصات فنی</h2>

            <h3><span class="method get">GET</span> لیست مشخصات</h3>
            <div class="endpoint"><code>{{url('api/admin')}}/specification</code></div>
            <p>پارامتر: <code>product_id=@{{product_simple_id}}</code></p>

            <h3><span class="method get">GET</span> مقادیر مشخصه</h3>
            <div class="endpoint"><code>{{url('api/admin')}}/specification/{id}/values</code></div>
            <p>مثال: <code>/specification/88/values</code></p>
            <h3><span class="method get">GET</span> محصولات دارای مشخصه خاص</h3>
            <div class="endpoint"><code>{{url('api/admin')}}/specification/products/{specification_id}</code></div>
            <p>مثال: <code>/specification/products/3069</code></p>
            <br>
            <h4>نمونه پاسخ:</h4>
            <div class="body-example">
                <pre>"data": [
    {
        "title": "ساعت مردانه دنیل گورمن مدل DG8127",
        "price": 0,
        "discounted_price": 0,
        "url": "dg8127",
        "specification_value": "https://zamanetim.com/product/sitizen-ew5622-09p"   <span class="comment"> //مقداری که برای این مشخصهی محصول وارد شده</span>
    }
]</pre>
            </div>
        </section>

        <!-- Blog Category -->
        <section id="blog-category">
            <h2>دسته‌بندی بلاگ</h2>

            <h3><span class="method get">GET</span> لیست</h3>
            <div class="endpoint"><code>{{url('api/admin')}}/blog-category/</code></div>

            <h3><span class="method get">GET</span> نمایش</h3>
            <div class="endpoint"><code>{{url('api/admin')}}/blog-category/show/{id}</code></div>

            <h3><span class="method post">POST</span> افزودن</h3>
            <div class="endpoint"> <code>{{url('api/admin')}}/blog-category/add/</code></div>
            <div class="body-example">
                <pre>{
    "title": "آموزش سئو و دیجیتال مارکتینگ",
    "description": "دسته‌بندی تخصصی مقالات آموزشی در زمینه سئو، تولید محتوا، تبلیغات آنلاین و افزایش ترافیک وب‌سایت.",
    "status": true,
    "parent_id": null,
    "url": "amoozesh-seo-digital-marketing",
    "seo": {
        "title_seo": "آموزش سئو و دیجیتال مارکتینگ | نکات کاربردی برای رشد کسب‌وکار",
        "description_seo": "بهترین مقالات آموزشی سئو، بهینه‌سازی محتوا، لینک‌سازی و استراتژی‌های دیجیتال مارکتینگ برای افزایش بازدید و فروش.",
        "h1": "آموزش سئو و دیجیتال مارکتینگ",
        "noindex": false
    }
}</pre>
            </div>
            <h3><span class="method put">PUT</span> ویرایش</h3>
            <div class="endpoint"> <code>{{url('api/admin')}}/blog-category/edit/{id}</code></div>
        </section>

        <!-- Blog -->
        <section id="blog">
            <h2>بلاگ</h2>

            <h3><span class="method get">GET</span> لیست مقالات</h3>
            <div class="endpoint"><code>{{url('api/admin')}}/blog/</code></div>

            <h3><span class="method get">GET</span> نمایش مقاله</h3>
            <div class="endpoint"><code>{{url('api/admin')}}/blog/show/{id}</code></div>

            <h3><span class="method post">POST</span> افزودن مقاله</h3>
            <div class="endpoint"><code>{{url('api/admin')}}/blog/add</code></div>
            <div class="body-example">
                <pre>{
    "title": "۳۲ نکته طلایی برای بهبود سئوی وبلاگ در سال ۱۴۰۴",
    "description": "در این مقاله جامع، ۳۲ تکنیک عملی و پیشرفته سئو را بررسی می‌کنیم که به شما کمک می‌کند ترافیک ارگانیک وبلاگ خود را تا ۳۰۰٪ افزایش دهید. از تحقیق کلمات کلیدی تا بهینه‌سازی محتوا و لینک‌سازی داخلی.",
    "url": "32-nekate-talaei-behtar-sazi-seo-weblog",
    "image": null,
    "status": true,
    "parent_id": 15,
    "show_in_first_page": true,
    "call_to_action": true,
    "publish_date": "1404/05/15",
    "author": "دکتر امیرحسین رضایی",
    "seo": {
        "title_seo": "۳۲ نکته طلایی سئو وبلاگ ۱۴۰۴ | راهنمای کامل افزایش رتبه گوگل",
        "description_seo": "با این ۳۲ تکنیک حرفه‌ای سئو، وبلاگ خود را به صفحه اول گوگل برسانید. شامل تحقیق کلمات کلیدی، محتوای باکیفیت، سرعت سایت و لینک‌سازی.",
        "h1": "۳۲ نکته طلایی برای بهبود سئوی وبلاگ در سال ۱۴۰۴",
        "noindex": false
    },
    "services": [19, 21, 22]
}</pre>
            </div>

            <h3><span class="method put">PUT</span> ویرایش مقاله</h3>
            <div class="endpoint"> <code>{{url('api/admin')}}/blog/edit/{id}</code></div>
        </section>
        <!-- Blog -->
        <section id="users">
            <h2>کاربران</h2>

            <h3><span class="method get">GET</span> لیست کاربران</h3>
            <div class="endpoint"><code>{{url('api/admin')}}/users/</code></div>

            <h3><span class="method post">POST</span> افزودن کاربران</h3>
            <div class="endpoint"><code>{{url('api/admin')}}/users/add</code></div>
            <div class="body-example">
                <pre>{
    "full_name": "علی عزیزی",
    "email": "ali.azizi@gmail.com.",  <span class="comment"> //فرمت صحیح داشته باشد</span>
    "mobile": "09xxxxxxxxx" <span class="comment"> //عدد باشد</span>
}</pre>
            </div>

            <h3><span class="method put">PUT</span> ویرایش کاربران</h3>
            <div class="endpoint"> <code>{{url('api/admin')}}/users/edit/{id}</code></div>
            <div class="body-example">
                <pre>{
    "full_name": "علی عزیزی",
    "email": "ali.azizi@gmail.com.",  <span class="comment"> //فرمت صحیح داشته باشد</span>
    "mobile": "09xxxxxxxxx" <span class="comment"> //عدد باشد</span>
}</pre>
            </div>
            <h3><span class="method put">PUT</span> حذف کاربران</h3>
            <div class="endpoint"> <code>{{url('api/admin')}}/users/delete/{id}</code></div>
        </section>
        <!-- Prerequisite -->
        <section id="prerequisite">
            <h2>اطلاعات بیشتر</h2>

            <!-- 3 لینک اصلی -->
            <h3><span class="method get">GET</span> برندها</h3>
            <div class="endpoint"><code>{{ url('api/admin/prerequisite/brands') }}</code></div>

            <h3><span class="method get">GET</span> تگ‌ها</h3>
            <div class="endpoint"><code>{{ url('api/admin/prerequisite/tags') }}</code></div>

            <h3><span class="method get">GET</span> خدمات</h3>
            <div class="endpoint"><code>{{ url('api/admin/prerequisite/services') }}</code></div>

            <!-- جدول پارامترهای مشترک -->
            <div class="params" style="margin-top: 25px;">
                <h4>پارامترهای کوئری (مشترک در <u>همه لیست‌ها</u>):</h4>
                <table>
                    <tr>
                        <th>پارامتر</th>
                        <th>نوع</th>
                        <th>توضیح</th>
                        <th>مثال</th>
                    </tr>
                    <tr>
                        <td><code>page</code></td>
                        <td>عدد</td>
                        <td>شماره صفحه (شروع از ۱)</td>
                        <td><code>page=2</code></td>
                    </tr>
                    <tr>
                        <td><code>title</code></td>
                        <td>رشته</td>
                        <td>جستجو در عنوان (جستجوی جزئی)</td>
                        <td><code>title=نایک</code></td>
                    </tr>
                </table>
            </div>

            <!-- تأکید بر یکسان بودن -->
            <div class="note" style="margin-top: 15px;">
                <strong>مهم:</strong> این پارامترها در <strong>تمام لیست‌های سیستم</strong> کار می‌کنند:
                <ul style="margin-top: 8px; columns: 2;">
                    <li><code>/product/</code></li>
                    <li><code>/product-category/</code></li>
                    <li><code>/blog/</code></li>
                    <li><code>/blog-category/</code></li>
                    <li><code>/prerequisite/brands/</code></li>
                    <li><code>/prerequisite/tags/</code></li>
                    <li><code>/prerequisite/services/</code></li>
                    <li><code>/specification</code></li>
                </ul>
            </div>

            <!-- مثال عملی -->
            <div class="body-example" style="margin-top: 20px;">
                <h4>نمونه درخواست (برندها، صفحه ۱، جستجوی "آدیداس"):</h4>
                <pre>{{ url('api/admin/prerequisite/brands') }}?title=آدیداس&page=1</pre>
            </div>

            <!-- جدول توضیح فیلدهای ابهام‌دار -->
            <div class="params" style="margin-top: 35px;">
                <h4>توضیح فیلدهای مهم و ابهام‌دار (در JSONها):</h4>
                <table style="margin-top: 10px;">
                    <tr>
                        <th>فیلد</th>
                        <th>مقدار</th>
                        <th>توضیح</th>
                    </tr>
                    <tr>
                        <td><code>active</code></td>
                        <td><code>true</code> / <code>false</code></td>
                        <td>آیا محصول/دسته‌بندی در سایت <strong>نمایش داده شود</strong>؟<br>
                            <code>true</code> = نمایش | <code>false</code> = مخفی (مثلاً در حال ویرایش)</td>
                    </tr>
                    <tr>
                        <td><code>status</code></td>
                        <td><code>true</code> / <code>false</code></td>
                        <td>در بلاگ و دسته‌بندی بلاگ: آیا محتوا <strong>منتشر شود</strong>؟<br>
                            <code>true</code> = منتشر | <code>false</code> = پیش‌نویس</td>
                    </tr>
                    <tr>
                        <td><code>show_in_first_page</code></td>
                        <td><code>true</code> / <code>false</code></td>
                        <td>آیا در <strong>صفحه اصلی</strong> نمایش داده شود؟<br>
                            فقط برای محصولات/دسته‌بندی‌های مهم استفاده شود.</td>
                    </tr>
                    <tr>
                        <td><code>noindex</code></td>
                        <td><code>true</code> / <code>false</code></td>
                        <td>آیا گوگل این صفحه را <strong>ایندکس کند</strong>؟<br>
                            <code>false</code> = ایندکس شود (عادی)<br>
                            <code>true</code> = مخفی از گوگل (فقط برای تست یا صفحات موقت)</td>
                    </tr>
                    <tr>
                        <td><code>parent_id</code></td>
                        <td>عدد یا <code>null</code></td>
                        <td>ID دسته‌بندی والد<br>
                            <code>null</code> = دسته‌بندی ریشه (اصلی)</td>
                    </tr>
                    <tr>
                        <td><code>main_variant_specification_id</code></td>
                        <td>عدد</td>
                        <td>ID مشخصه اصلی برای واریانت (مثل وزن، رنگ)<br>
                            <strong>الزامی در product/add</strong></td>
                    </tr>
                    <tr>
                        <td><code>stock</code></td>
                        <td>عدد</td>
                        <td>موجودی انبار — اگر ۰ باشد، "ناموجود" نمایش داده می‌شود</td>
                    </tr>
                </table>
            </div>

            <!-- نمونه پاسخ پیجینیشن -->
            <div class="response-example" style="margin-top: 25px;">
                <h4>نمونه پاسخ (ساختار پیجینیشن — مشترک در همه لیست‌ها):</h4>
<pre>{
    "success" : true,
    "page": 1,
    "data": [...],
}</pre>
            </div>
    <!-- نمونه ویرایش -->
    <div class="body-example" style="margin-top: 20px">
        <h4>نمونه ویرایش (فقط فیلدی که قراره تفییر کنه):</h4>
        <pre>{
    "stock": 80
}</pre>
        <p style="font-size: 0.9rem; color: #555; margin-top: 8px;">
            فقط <code>stock</code> تغییر کرد → بقیه فیلدها دست نخورده میمانند.
        </p>
        <p style="font-size: 0.9rem; color: #555; margin-top: 4px;">
            این در مورد همه ویرایش ها صدق میکند
        </p>
    </div>
    <div>
        <div class="download-section">
            <h2>دانلود Collection Postman</h2>
            <p>برای تست سریع تمام APIها، فایل JSON Collection را دانلود و در Postman import کنید.</p>

            <a href="{{ asset('assets/admin/api-cms.json') }}"
            download="cms-api-postman-collection.json"
            class="download-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16" style="margin-left: 10px;">
                    <path d="M.5 9.9a.5.5 0 0 1 .5-.5h4V2a.5.5 0 0 1 1 0v7.5h4a.5.5 0 0 1 .354.854l-4 4a.5.5 0 0 1-.708 0l-4-4A.5.5 0 0 1 .5 9.9z"/>
                    <path d="M15.5 8h-5V3a.5.5 0 0 0-1 0v5h-5a.5.5 0 0 0 0 1h5v5a.5.5 0 0 0 1 0v-5h5a.5.5 0 0 0 0-1z"/>
                </svg>
                دانلود فایل Postman (JSON)
            </a>
        </div>
        </div>
        </section>

        <!-- Footer -->
        <footer>
            <p>مستندات API سیستم مدیریت محتوا | تاریخ به‌روزرسانی: 16 آبان 1404</p>
            <p>برای پشتیبانی با تیم توسعه <strong>همگامان</strong> تماس بگیرید.</p>
        </footer>
    </div>
</body>
</html>
