## ✅ 1. جلوگیری از تداخل `{{ }}` بین Blade و Vue

Blade و Vue هر دو از `{{ }}` استفاده می‌کنن → مشکل اصلی همینه.

### ✔ بهترین روش:

در Vue delimiter رو تغییر بده:

```html
<script>
new Vue({
  el: '#app',
  delimiters: ['[[', ']]'],
  data: {
    name: 'Ali'
  }
})
</script>
```

```html
<div id="app">
  [[ name ]]
</div>
```

📌 از این به بعد:

* `{{ }}` فقط برای Blade
* `[[ ]]` فقط برای Vue

---

## ✅ 2. همیشه Vue رو داخل یک container مشخص محدود کن

❌ بد:

```html
<div>
  <p>[[ name ]]</p>
</div>
```

✔ خوب:

```html
<div id="app">
  <p>[[ name ]]</p>
</div>
```

📌 باعث میشه Vue روی کل صفحه Blade اثر نذاره.

---

## ✅ 3. چاپ شرطی → فقط با Vue، نه Blade

❌ اشتباه رایج:

```blade
@if($user)
  <p>[[ name ]]</p>
@endif
```

✔ درست:

```html
<p v-if="user">[[ name ]]</p>
```

📌 منطق نمایشی = Vue
📌 منطق سرور = Blade

---

## ✅ 4. لیست‌ها رو فقط با `v-for` چاپ کن

❌ بد:

```blade
@foreach($items as $item)
  <li>[[ item.name ]]</li>
@endforeach
```

✔ درست:

```html
<li v-for="item in items" :key="item.id">
  [[ item.name ]]
</li>
```

📌 داده‌ها از Laravel → JSON → Vue

---

## ✅ 5. داده‌های Laravel رو فقط یک‌بار به Vue تزریق کن

✔ استاندارد امن:

```blade
<script>
window.appData = @json($data);
</script>
```

```html
<script>
new Vue({
  el: '#app',
  data: {
    items: window.appData.items
  }
})
</script>
```

📌 از چاپ مستقیم متغیرهای Blade وسط HTML Vue خودداری کن.

---

## ✅ 6. چاپ متن خام vs HTML

| هدف            | دستور                  |
| -------------- | ---------------------- |
| چاپ امن متن    | `[[ text ]]`           |
| چاپ HTML واقعی | `v-html="htmlContent"` |

❌ اشتباه:

```html
<p>[[ htmlText ]]</p>
```

✔ درست:

```html
<p v-html="htmlText"></p>
```

⚠️ فقط وقتی منبع امنه.

---

## ✅ 7. رویدادها همیشه Vue‌ای، نه inline JS

❌ بد:

```html
<button onclick="doSomething()">کلیک</button>
```

✔ درست:

```html
<button @click="doSomething">کلیک</button>
```

---

## ✅ 8. تفکیک ذهنی مهم (قانون طلایی)

| کار             | مسئول       |
| --------------- | ----------- |
| رندر اولیه صفحه | Blade       |
| تعاملات UI      | Vue         |
| شرط‌ها، لوپ‌ها  | Vue         |
| دریافت دیتا     | API یا JSON |
| ساخت layout     | Blade       |

---

## ✅ 9. ساختار پیشنهادی تمیز در Blade + Vue CDN

```blade
<div id="app">
  <ul>
    <li v-for="item in items" :key="item.id">
      [[ item.title ]]
    </li>
  </ul>

  <button @click="addItem">افزودن</button>
</div>

<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
<script>
new Vue({
  el: '#app',
  delimiters: ['[[', ']]'],
  data: {
    items: window.appData.items
  },
  methods: {
    addItem() {
      this.items.push({ id: Date.now(), title: 'جدید' })
    }
  }
})
</script>
```

---

## 🔥 10. قوانینی که به هوش مصنوعی هم بده (Golden Rules)

اگر بخوای به AI بدی، اینو بده:

> * در Blade + Vue2 CDN همیشه delimiters رو `[[ ]]` قرار بده
> * هیچ `@if` یا `@foreach` داخل template Vue ننویس
> * همه چاپ‌ها با `[[ ]]`
> * داده‌ها فقط از `window.appData` بیان
> * هیچ `onclick` یا JS inline ننویس
> * Vue فقط داخل `#app` اجرا شود
