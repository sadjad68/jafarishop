# SEO Meta Structure

## Overview
سیستم مدیریت SEO برای صفحات استاتیک و داینامیک با استفاده از Polymorphic Relationship.

---

## Architecture

### Components
- **Model**: `App\Models\SeoMeta` - با رابطه `morphTo`
- **Service**: `App\Services\SeoMetaService` - منطق کسب‌وکار
- **Controller**: `App\Http\Controllers\Admin\SeoMetaController`
- **DTO**: `App\DTO\SeoMetaDTO`
- **Request**: `App\Http\Requests\SeoMetaRequest`

---

## Database Structure

### Table: `seo_metas`
```php
- id
- seoable_type (nullable) // برای صفحات داینامیک
- seoable_id (nullable)    // برای صفحات داینامیک
- url (nullable)           // برای صفحات استاتیک
- title_seo (nullable)
- description_seo (nullable)
- noindex (boolean, default: false)
- timestamps
```

**نکته**: صفحات استاتیک از `url` استفاده می‌کنند، صفحات داینامیک از `seoable_type` و `seoable_id`.

---

## Types

### 1. Static Pages (صفحات استاتیک)
- مدیریت از طریق صفحه "سئو صفحات"
- مثال: صفحه اصلی (`/`)

### 2. Dynamic Pages (صفحات داینامیک)
- مدیریت از طریق دکمه "G" در لیست‌ها
- مثال: Article, Product, Category, Brand

---

## Usage in Views

### در صفحات لیست (Dynamic)
```blade
@include('admin._partials.seo-form', [
    'data' => $row,
    'button' => 'icon'  // یا 'full' برای استایل کامل
])
```

**پارامترها:**
- `data`: مدل Eloquent (Article, Product, Category, Brand)
- `button`: `'icon'` (پیش‌فرض) یا `'full'` (برای لیست محصولات)

**نکته**: فایل include شامل دکمه و modal است. نیازی به کد اضافی نیست.

---

## Routes

### Static Pages
- `GET /admin/seo-meta` - لیست صفحات استاتیک
- `GET /admin/seo-meta/edit/{id}` - ویرایش صفحه استاتیک
- `POST /admin/seo-meta/update/{id}` - به‌روزرسانی صفحه استاتیک

### Dynamic Pages
- `GET /admin/seo-meta/dynamic/{type}/{id}` - دریافت SEO
- `POST /admin/seo-meta/dynamic/{type}/{id}` - ذخیره/به‌روزرسانی SEO

---

## Service Methods

### Static Pages
```php
getStaticPages(): array
getStaticPage(int $id): array
updateStatic(SeoMetaDTO $dto, int $id): RedirectResponse
```

### Dynamic Pages
```php
getDynamicSeo(string $type, int $id): ?SeoMeta
createOrUpdateDynamic(SeoMetaDTO $dto, string $type, int $id): void
```

---

## Important Notes

1. **Polymorphic Relationship**: مدل `SeoMeta` از `morphTo` استفاده می‌کند
2. **Validation**: در `SeoMetaRequest` - یا `url` یا `seoable_type` + `seoable_id` الزامی است
3. **Include File**: `admin._partials.seo-form` شامل دکمه و modal است
4. **Auto Detection**: نوع مدل و ID به صورت خودکار از `$data` استخراج می‌شود

---

## Example

### افزودن SEO به یک لیست جدید
```blade
{{-- در فایل index.blade.php --}}
<th>
    <div class="btn-group">
        <a href="{{ route('admin.example.edit', ['id' => $row->id]) }}">
            <i class="bi bi-pencil-square"></i>
        </a>
        @include('admin._partials.seo-form', [
            'data' => $row,
            'button' => 'icon'
        ])
    </div>
</th>
```

**نکته**: مدل باید دارای `id` باشد. نوع مدل به صورت خودکار از `get_class($data)` استخراج می‌شود.







