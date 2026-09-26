بله، برای CMS فروشگاهی شما باید ساختار را کمی عمومی تر و استانداردتر کنید. مخصوصا برای دو موضوعی که گفتید:

1. مرحله آدرس و روش ارسال
2. محصولات چند متغیره که همیشه رنگ و سایز نیستند

## 1. ترتیب پیشنهادی ایونت ها در CMS شما

برای فروشگاه ساز، بهتر است این فلو را به عنوان استاندارد داخل CMS بگذارید:

| مرحله کاربر                 | Event پیشنهادی GA4  |
| --------------------------- | ------------------- |
| مشاهده لیست محصولات         | `view_item_list`    |
| کلیک روی محصول از لیست      | `select_item`       |
| مشاهده صفحه محصول           | `view_item`         |
| افزودن به سبد خرید          | `add_to_cart`       |
| حذف از سبد خرید             | `remove_from_cart`  |
| مشاهده سبد خرید             | `view_cart`         |
| شروع چک اوت                 | `begin_checkout`    |
| ثبت آدرس / انتخاب روش ارسال | `add_shipping_info` |
| انتخاب روش پرداخت           | `add_payment_info`  |
| پرداخت موفق                 | `purchase`          |

برای مرحله آدرس و ارسال، ایونت درست GA4 همان `add_shipping_info` است. گوگل دقیقا گفته وقتی کاربر در چک اوت اطلاعات ارسال را ثبت می کند یا روش ارسال را انتخاب می کند، باید این ایونت ارسال شود و مقدار `shipping_tier` برای نوع ارسال استفاده شود. ([Google for Developers][1])

---

## 2. نکته خیلی مهم درباره آدرس کاربر

در `dataLayer` نباید آدرس کامل، کد پستی، شماره موبایل، ایمیل، نام و نام خانوادگی یا اطلاعات قابل شناسایی کاربر را ارسال کنید. گوگل صراحتا ارسال PII مثل ایمیل، شماره موبایل و اطلاعات قابل شناسایی را به Google Analytics ممنوع می داند. ([Google Help][2])

پس برای مرحله آدرس، این کار را نکنید:

```js
address: 'تهران، خیابان ...',
phone: '0912...',
email: '...',
postal_code: '...'
```

به جایش فقط چیزهای غیرشخصی و تحلیلی بفرستید، مثلا:

```js
shipping_tier: 'پست پیشتاز'
```

یا نهایتا:

```js
shipping_tier: 'ارسال فوری تهران'
```

---

## 3. مشکل محصولات چند متغیره را اینطوری حل کنید

الان شما `item_size` و `item_color` دارید. ولی چون CMS شما عمومی است، ممکن است یک محصول متغیرهای دیگری داشته باشد، مثلا:

* رنگ
* سایز
* جنس
* گارانتی
* ولتاژ
* حافظه
* طعم
* بسته بندی
* مدل
* کشور سازنده

پس بهتر است به جای اینکه برای هر ویژگی یک پارامتر جدا و متغیر بسازید، یک مدل ثابت داشته باشید.

### ساختار پیشنهادی آیتم برای CMS

```js
{
  item_id: 'A-4022-NAVY-36',
  item_name: 'کفش زنانه برتونیکس A-4022',
  item_category: 'کفش زنانه',
  item_category2: 'کفش چرم',
  item_brand: 'Bertonix',
  item_variant: 'رنگ: سرمه ای | سایز: 36',
  variant_id: '98765',
  variant_options: 'رنگ=سرمه ای|سایز=36',
  option_1_name: 'رنگ',
  option_1_value: 'سرمه ای',
  option_2_name: 'سایز',
  option_2_value: '36',
  price: 6200000,
  quantity: 1
}
```

چرا این مدل بهتر است؟ چون `item_variant` پارامتر استاندارد GA4 است و برای خلاصه متغیر محصول استفاده می شود. از طرف دیگر، GA4 اجازه می دهد داخل `items` تا 27 پارامتر سفارشی آیتمی هم اضافه کنید، ولی برای تحلیل در گزارش ها باید آن ها را به عنوان Item-scoped custom dimension تعریف کنید. ([Google for Developers][3])

برای CMS عمومی، این مدل بهتر از `item_color` و `item_size` است:

```js
option_1_name
option_1_value
option_2_name
option_2_value
option_3_name
option_3_value
variant_options
variant_id
```

چون اسم پارامترها همیشه ثابت می ماند، ولی مقدارشان بر اساس نوع محصول تغییر می کند.

---

## 4. نکته مهم درباره `item_id`

برای محصولات چند متغیره، بهتر است `item_id` شناسه همان متغیر قابل خرید باشد، نه فقط محصول مادر.

مثلا اگر محصول مادر این است:

```txt
A-4022
```

ولی متغیر انتخاب شده این است:

```txt
A-4022 / سرمه ای / سایز 36
```

بهتر است `item_id` این باشد:

```js
item_id: 'A-4022-NAVY-36'
```

یا اگر در دیتابیس برای هر واریانت ID دارید:

```js
item_id: 'VAR-98765'
```

و محصول مادر را در پارامتر سفارشی نگه دارید:

```js
parent_item_id: 'A-4022'
```

در GA4 حداقل یکی از `item_id` یا `item_name` برای آیتم لازم است، ولی برای گزارش های دقیق و اتصال به تبلیغات، بهتر است هر دو را همیشه بفرستید. ([Google for Developers][4])

---

## 5. کد استاندارد پیشنهادی برای ارسال ایونت ها

بهتر است داخل CMS یک تابع مرکزی داشته باشید تا همه پروژه ها از یک ساختار یکسان استفاده کنند:

```js
function pushEcommerceEvent(eventName, ecommerceData) {
  window.dataLayer = window.dataLayer || [];

  window.dataLayer.push({ ecommerce: null });

  window.dataLayer.push({
    event: eventName,
    ecommerce: ecommerceData
  });
}
```

بعد برای هر مرحله فقط همین تابع را صدا بزنید.

---

## 6. نمونه `view_item` برای محصول چند متغیره

```js
pushEcommerceEvent('view_item', {
  currency: 'IRR',
  value: 62000000,
  items: [{
    item_id: 'A-4022-NAVY-36',
    parent_item_id: 'A-4022',
    variant_id: '98765',
    item_name: 'کفش زنانه برتونیکس A-4022',
    item_brand: 'Bertonix',
    item_category: 'کفش زنانه',
    item_category2: 'کفش چرم',
    item_variant: 'رنگ: سرمه ای | سایز: 36',
    variant_options: 'رنگ=سرمه ای|سایز=36',
    option_1_name: 'رنگ',
    option_1_value: 'سرمه ای',
    option_2_name: 'سایز',
    option_2_value: '36',
    price: 62000000,
    quantity: 1
  }]
});
```

نکته: اگر واحد قیمت سایت تومان است، برای `currency: 'IRR'` بهتر است عدد را به ریال بفرستید. یعنی 6,200,000 تومان را به صورت `62000000` بفرستید. چون `currency` باید کد سه حرفی استاندارد باشد و اگر `value` می فرستید، ارسال `currency` هم لازم است. ([Google for Developers][4])

---

## 7. نمونه `add_to_cart`

```js
pushEcommerceEvent('add_to_cart', {
  currency: 'IRR',
  value: 62000000,
  items: [{
    item_id: 'A-4022-NAVY-36',
    parent_item_id: 'A-4022',
    variant_id: '98765',
    item_name: 'کفش زنانه برتونیکس A-4022',
    item_brand: 'Bertonix',
    item_category: 'کفش زنانه',
    item_variant: 'رنگ: سرمه ای | سایز: 36',
    variant_options: 'رنگ=سرمه ای|سایز=36',
    option_1_name: 'رنگ',
    option_1_value: 'سرمه ای',
    option_2_name: 'سایز',
    option_2_value: '36',
    price: 62000000,
    quantity: 1
  }]
});
```

برای `add_to_cart`، مقدار `value` بهتر است برابر قیمت همان محصول ضربدر تعداد اضافه شده باشد.

---

## 8. نمونه `begin_checkout`

```js
pushEcommerceEvent('begin_checkout', {
  currency: 'IRR',
  value: 248000000,
  coupon: 'SUMMER10',
  items: [
    {
      item_id: 'A-4022-NAVY-36',
      parent_item_id: 'A-4022',
      variant_id: '98765',
      item_name: 'کفش زنانه برتونیکس A-4022',
      item_brand: 'Bertonix',
      item_category: 'کفش زنانه',
      item_variant: 'رنگ: سرمه ای | سایز: 36',
      variant_options: 'رنگ=سرمه ای|سایز=36',
      option_1_name: 'رنگ',
      option_1_value: 'سرمه ای',
      option_2_name: 'سایز',
      option_2_value: '36',
      price: 62000000,
      quantity: 3
    },
    {
      item_id: 'K-582-KHAKI-36',
      parent_item_id: 'K-582',
      variant_id: '98766',
      item_name: 'کفش زنانه برتونیکس K-582',
      item_brand: 'Bertonix',
      item_category: 'کفش زنانه',
      item_variant: 'رنگ: خاکی | سایز: 36',
      variant_options: 'رنگ=خاکی|سایز=36',
      option_1_name: 'رنگ',
      option_1_value: 'خاکی',
      option_2_name: 'سایز',
      option_2_value: '36',
      price: 62000000,
      quantity: 1
    }
  ]
});
```

`value` باید جمع `price * quantity` آیتم ها باشد و بهتر است مالیات و ارسال را داخل `value` قاطی نکنید؛ گوگل برای `value` همین جمع آیتم ها را پیشنهاد می کند و `shipping` و `tax` جدا هستند. ([Google for Developers][4])

---

## 9. نمونه مرحله آدرس و روش ارسال: `add_shipping_info`

این همان مرحله ای است که گفتید در CMS دارید.

```js
pushEcommerceEvent('add_shipping_info', {
  currency: 'IRR',
  value: 248000000,
  coupon: 'SUMMER10',
  shipping_tier: 'پست پیشتاز',
  items: [
    {
      item_id: 'A-4022-NAVY-36',
      parent_item_id: 'A-4022',
      variant_id: '98765',
      item_name: 'کفش زنانه برتونیکس A-4022',
      item_brand: 'Bertonix',
      item_category: 'کفش زنانه',
      item_variant: 'رنگ: سرمه ای | سایز: 36',
      variant_options: 'رنگ=سرمه ای|سایز=36',
      option_1_name: 'رنگ',
      option_1_value: 'سرمه ای',
      option_2_name: 'سایز',
      option_2_value: '36',
      price: 62000000,
      quantity: 3
    },
    {
      item_id: 'K-582-KHAKI-36',
      parent_item_id: 'K-582',
      variant_id: '98766',
      item_name: 'کفش زنانه برتونیکس K-582',
      item_brand: 'Bertonix',
      item_category: 'کفش زنانه',
      item_variant: 'رنگ: خاکی | سایز: 36',
      variant_options: 'رنگ=خاکی|سایز=36',
      option_1_name: 'رنگ',
      option_1_value: 'خاکی',
      option_2_name: 'سایز',
      option_2_value: '36',
      price: 62000000,
      quantity: 1
    }
  ]
});
```

اینجا `shipping_tier` می تواند یکی از این ها باشد:

```txt
پست پیشتاز
تیپاکس
ارسال فوری
پیک موتوری
دریافت حضوری
ارسال رایگان
```

---

## 10. نمونه `add_payment_info`

```js
pushEcommerceEvent('add_payment_info', {
  currency: 'IRR',
  value: 248000000,
  coupon: 'SUMMER10',
  payment_type: 'درگاه بانک اقتصاد نوین',
  items: [
    {
      item_id: 'A-4022-NAVY-36',
      parent_item_id: 'A-4022',
      variant_id: '98765',
      item_name: 'کفش زنانه برتونیکس A-4022',
      item_brand: 'Bertonix',
      item_category: 'کفش زنانه',
      item_variant: 'رنگ: سرمه ای | سایز: 36',
      variant_options: 'رنگ=سرمه ای|سایز=36',
      option_1_name: 'رنگ',
      option_1_value: 'سرمه ای',
      option_2_name: 'سایز',
      option_2_value: '36',
      price: 62000000,
      quantity: 3
    }
  ]
});
```

---

## 11. نمونه `purchase`

```js
pushEcommerceEvent('purchase', {
  transaction_id: 'ORD-20202000',
  currency: 'IRR',
  value: 248000000,
  tax: 0,
  shipping: 500000,
  coupon: 'SUMMER10',
  payment_type: 'درگاه بانک اقتصاد نوین',
  shipping_tier: 'پست پیشتاز',
  items: [
    {
      item_id: 'A-4022-NAVY-36',
      parent_item_id: 'A-4022',
      variant_id: '98765',
      item_name: 'کفش زنانه برتونیکس A-4022',
      item_brand: 'Bertonix',
      item_category: 'کفش زنانه',
      item_variant: 'رنگ: سرمه ای | سایز: 36',
      variant_options: 'رنگ=سرمه ای|سایز=36',
      option_1_name: 'رنگ',
      option_1_value: 'سرمه ای',
      option_2_name: 'سایز',
      option_2_value: '36',
      price: 62000000,
      quantity: 3
    },
    {
      item_id: 'K-582-KHAKI-36',
      parent_item_id: 'K-582',
      variant_id: '98766',
      item_name: 'کفش زنانه برتونیکس K-582',
      item_brand: 'Bertonix',
      item_category: 'کفش زنانه',
      item_variant: 'رنگ: خاکی | سایز: 36',
      variant_options: 'رنگ=خاکی|سایز=36',
      option_1_name: 'رنگ',
      option_1_value: 'خاکی',
      option_2_name: 'سایز',
      option_2_value: '36',
      price: 62000000,
      quantity: 1,
      item_coupon: 'ITEM10'
    }
  ]
});
```

برای `purchase` حتما `transaction_id` یکتا بفرستید و ایونت را فقط یک بار بعد از پرداخت موفق اجرا کنید. خود گوگل هم تاکید می کند `transaction_id` به جلوگیری از ثبت خرید تکراری کمک می کند. ([Google for Developers][4])

---

## 12. جمع بندی پیشنهادی برای CMS شما

برای CMS شرکتی شما، بهترین مدل این است:

* داخل تنظیمات هر سایت، کاربر فقط GTM ID یا GA4 Measurement ID را وارد کند.
* CMS به صورت خودکار همه ایونت های ecommerce را push کند.
* برای محصولات چند متغیره، از ساختار ثابت `item_variant` + `variant_options` + `option_1_name/value` استفاده کنید.
* `item_id` را شناسه واریانت قابل خرید بگذارید، نه فقط محصول مادر.
* مرحله آدرس و ارسال را با `add_shipping_info` بفرستید.
* هیچ اطلاعات شخصی مثل آدرس، موبایل، ایمیل، نام و کد پستی را به GA4 نفرستید.
* برای همه ایونت های ecommerce، قبل از push اصلی این خط را بزنید:

```js
window.dataLayer.push({ ecommerce: null });
```

* قیمت ها را عددی بفرستید، نه رشته ای.
* اگر واحد پولی ایران است، بهتر است `currency: 'IRR'` و مبلغ به ریال باشد.
* برای هر ایونت `value` را برابر جمع `price * quantity` آیتم ها بگذارید، بدون مالیات و ارسال.
* برای `purchase`، `shipping` و `tax` را جداگانه بفرستید.

[1]: https://developers.google.com/analytics/devguides/collection/ga4/ecommerce "Measure ecommerce  |  Google Analytics  |  Google for Developers"
[2]: https://support.google.com/analytics/answer/6366371?hl=en "Best practices to avoid sending Personally Identifiable Information (PII) - Analytics Help"
[3]: https://developers.google.com/analytics/devguides/collection/ga4/item-scoped-ecommerce "Create item-scoped custom parameters  |  Google Analytics  |  Google for Developers"
[4]: https://developers.google.com/analytics/devguides/collection/ga4/reference/events "Recommended events  |  Google Analytics  |  Google for Developers"
