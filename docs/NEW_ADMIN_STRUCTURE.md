# مستندات ساختار  Admin

این مستند ساختار پوشه `new-admin` و کامپوننت‌های قابل استفاده را توضیح می‌دهد.

## ساختار پوشه new-admin

```
resources/views/new-admin/│
├── _layout/                  # Layout های اصلی
│   ├── master.blade.php       # Layout اصلی
│   └── blocks/                # بلوک‌های Layout
│       ├── head.blade.php
│       ├── header.blade.php
│       ├── inner-sidebar.blade.php
│       ├── mobile-sidebar.blade.php
│       ├── script.blade.php
│       ├── sidebar.blade.php
│       └── utils/             # ابزارهای کمکی
│           ├── ckeditor-scripts.blade.php
│           ├── confirmDelete.blade.php
│           ├── confirmDeleteService.blade.php
│           ├── image.blade.php
│           ├── page-getter.blade.php
│           ├── search-input.blade.php
│           └── see-more-btn.blade.php
│
├── components/                # کامپوننت‌های قابل استفاده مجدد
│   ├── admin/
│   │   ├── copy-button.blade.php
│   │   └── url-validate.blade.php
│   ├── back-button.blade.php
│   ├── cropper/
│   │   └── cropper.blade.php
│   ├── forms/                 # کامپوننت‌های فرم
│   │   ├── check-box.blade.php
│   │   ├── ck-editor.blade.php
│   │   ├── image-input-old.blade.php
│   │   ├── image-input.blade.php
│   │   ├── input.blade.php
│   │   ├── multi-select.blade.php
│   │   ├── multiple-image-input-withOutCropper.blade.php
│   │   ├── multiple-image-input.blade.php
│   │   ├── password-input.blade.php
│   │   ├── select.blade.php
│   │   └── text-area.blade.php
│   ├── pagination/
│   │   └── default.blade.php
│   ├── show-first-page.blade.php
│   ├── sweetalert.blade.php
│   ├── theme/
│   │   ├── select_box_color.blade.php
│   │   └── select_box.blade.php
│   └── video-button.blade.php
│
└── {section}/                  # بخش‌های CRUD (مثال: blog)
    ├── index.blade.php         # صفحه لیست
    ├── create.blade.php        # صفحه افزودن
    ├── edit.blade.php          # صفحه ویرایش
    ├── _form.blade.php         # فرم مشترک (برای create و edit)
    └── _search.blade.php       # فرم جستجو (اختیاری)
```

## ساختار CRUD نمونه (Blog)

### 1. صفحه لیست (index.blade.php)

```blade
@extends('new-admin._layout.master')

@section('title')
مطالب
@stop

@section('content')
<div class="body d-flex py-3" id="cms-form">
    <div class="container-fluid">
        <div class="page-header">
            <div class="card-header py-3 no-bg bg-transparent border-0 px-0 flex-wrap">
                <h3 class="fw-bolder mb-0">مطالب</h3>
                <div class="d-flex flex-md-row flex-column align-items-md-center justify-content-md-between w-100">
                    <a href="{{route('admin.blog.create')}}" class="btn ms-2 my-2 btn-custom-b rounded-custom">
                        <i class="bi bi-plus-square-dotted d-flex h5 my-0 me-2"></i>
                        افزودن مطلب
                    </a>
                    <ul class="list-inline align-items-center m-0">
                        <li class="list-inline-item mx-0">
                            <a data-bs-target="#searchModal" data-bs-toggle="modal" class="btn my-2 btn-custom rounded-custom">
                                <i class="bi bi-search d-flex my-0 me-2"></i>
                                جستجوی پیشرفته
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid">
        <div class="card-block row">
            <div class="col-sm-12 col-lg-12 col-xl-12">
                <div class="table-responsive">
                    <table id="myDataTable" class="table align-middle border-custom mb-0">
                        <thead class="text-center text-light">
                            <tr>
                                <th>#</th>
                                <th>عنوان</th>
                                <th>تصویر</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody class="text-center text-light">
                            @foreach($items as $key => $row)
                            <tr>
                                <th>{{$key + 1}}</th>
                                <th>{{$row['title']}}</th>
                                <th>
                                    <img src="{{@$row->getItemImage()}}" class="border shadow rounded" style="width:50px">
                                </th>
                                <th>
                                    <div class="btn-group" role="group">
                                        <a href="{{route('admin.blog.edit',['id'=>$row->id])}}" data-bs-toggle="tooltip" data-bs-title="ویرایش">
                                            <i class="bi bi-pencil-square color-custom2 fs-5"></i>
                                        </a>
                                        <a onclick="confirmDelete('{{route('admin.blog.delete',['id'=>$row->id])}}')" href="#" data-bs-toggle="tooltip" data-bs-title="حذف">
                                            <i class="bi bi-trash3 color-custom2 fs-5"></i>
                                        </a>
                                    </div>
                                </th>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @include("new-admin.components.pagination.default",['paginator'=>$items])
            </div>
        </div>
    </div>
</div>
@stop

@push('scripts')
@include('new-admin._layout.blocks.utils.confirmDelete')
@endpush
```

### 2. صفحه افزودن (create.blade.php)

```blade
@extends('new-admin._layout.master')

@section('title') افزودن مطلب @stop

@section('content')
<div class="body d-flex py-3">
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="border-0 mb-4">
                    <div class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                        <h3 class="fw-bolder mb-0">افزودن مطلب</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid">
        <div class="card border p-2">
            <form action="{{route('admin.blog.create')}}" method="POST" enctype="multipart/form-data" id="cms-form" @submit.prevent="validateForm">
                @csrf
                @include('new-admin.blog._form')
            </form>
        </div>
    </div>
</div>
@stop

@push('scripts')
@include('new-admin._layout.blocks.utils.ckeditor-scripts')
<script src="{{asset('assets/new-admin/js/vue.js')}}"></script>
<script src="{{asset('assets/new-admin/js/validations.js')}}"></script>
@endpush
```

### 3. صفحه ویرایش (edit.blade.php)

```blade
@extends('new-admin._layout.master')

@section('title') ویرایش مطلب @stop

@section('content')
<div class="body d-flex py-3">
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="border-0 mb-4">
                    <div class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                        <h3 class="fw-bolder mb-0">ویرایش مطلب</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid">
        <div class="card border p-2">
            <form action="{{route('admin.blog.edit',['id'=>$data->id])}}" method="POST" enctype="multipart/form-data" id="cms-form" @submit.prevent="validateForm">
                @csrf
                @include('new-admin.blog._form')
            </form>
        </div>
    </div>
</div>
@stop

@push('scripts')
@include('new-admin._layout.blocks.utils.ckeditor-scripts')
<script src="{{asset('assets/new-admin/js/vue.js')}}"></script>
<script src="{{asset('assets/new-admin/js/validations.js')}}"></script>
@endpush
```

### 4. فرم مشترک (_form.blade.php)

```blade
<div class="container-fluid">
    <div class="card-block row w-100 m-0">
        <div class="col-xxl-3 col-sm-6 p-2">
            <div class="form-group">
                <x-form-input name="title" label="عنوان" :validations="['requiredCms']" type="text" :value="@$data" />
            </div>
        </div>
        
        <div class="col-xxl-3 col-sm-6 p-2">
            <div class="form-group">
                <x-form-input name="url" label="آدرس url" :validations="['requiredCms','urlCms']" type="text" :value="@$data" />
            </div>
        </div>
        
        <div class="col-xxl-3 col-sm-6 p-2">
            <x-form-select name="parent_id" label="دسته بندی" :options="$category" optionValue="id" optionLabel="title" :searchable="true" :selectedOption="isset($data->parent_id) ? $data->parent_id : null" />
        </div>
        
        <div class="col-xxl-3 col-sm-6 p-2">
            <x-form-image-input name="image" label="تصویر" :imageSrc="(isset($data) && $data->image) ? $data->getItemImage() : null" :validations="['requiredCms']" width="450" height="450" cropper="1" />
        </div>
        
        <div class="col-12 p-2">
            <div class="form-group">
                <x-form-ck-editor name="description" label="توضیحات" type="text" :value="@$data" />
            </div>
        </div>
        
        <div class="w-100 pe-0">
            @include('new-admin._layout.blocks.utils.page-getter')
            <button type="submit" id="submitFormCms" class="btn btn-custom rounded-custom w-fit px-3 py-2">
                ذخیره
            </button>
        </div>
    </div>
</div>
```

## کامپوننت‌های فرم

### 1. x-form-input

کامپوننت ورودی متنی

```blade
<x-form-input 
    name="title" 
    label="عنوان" 
    type="text"
    :validations="['requiredCms']"
    :value="@$data" 
/>
```

**پارامترها:**
- `name`: نام فیلد
- `label`: برچسب فیلد
- `type`: نوع input (text, email, number, ...)
- `validations`: آرایه validation ها (مثلاً: `['requiredCms', 'urlCms']`)
- `value`: داده موجود (برای edit)

### 2. x-form-select

کامپوننت انتخاب (Select)

```blade
<x-form-select 
    name="parent_id" 
    label="دسته بندی" 
    :options="$category" 
    optionValue="id" 
    optionLabel="title"
    :searchable="true"
    :selectedOption="isset($data->parent_id) ? $data->parent_id : null" 
/>
```

**پارامترها:**
- `name`: نام فیلد
- `label`: برچسب فیلد
- `options`: آرایه گزینه‌ها
- `optionValue`: فیلد value
- `optionLabel`: فیلد label
- `searchable`: قابل جستجو بودن
- `selectedOption`: مقدار انتخاب شده

### 3. x-form-multi-select

کامپوننت انتخاب چندتایی

```blade
<x-form-multi-select 
    name="services" 
    label="خدمات" 
    :options="$services" 
    optionValue="id" 
    optionLabel="title"
    :searchable="true"
    :selectedOptions="isset($data) ? $data->services->map(function($item){return $item->id;})->toArray() : []" 
/>
```

### 4. x-form-image-input

کامپوننت آپلود تصویر با Cropper

```blade
<x-form-image-input 
    name="image" 
    label="تصویر (سایز : w450 * h450 )"
    :imageSrc="(isset($data) && $data->image) ? $data->getItemImage() : null" 
    :validations="['requiredCms']"
    width="450" 
    height="450" 
    cropper="1" 
/>
```

**پارامترها:**
- `name`: نام فیلد
- `label`: برچسب فیلد
- `imageSrc`: آدرس تصویر موجود (برای edit)
- `validations`: آرایه validation ها
- `width`: عرض تصویر
- `height`: ارتفاع تصویر
- `cropper`: فعال/غیرفعال کردن cropper (1 یا 0)

### 5. x-form-ck-editor

کامپوننت ویرایشگر متن (CKEditor)

```blade
<x-form-ck-editor 
    name="description" 
    label="توضیحات" 
    type="text" 
    :value="@$data" 
/>
```

**نکته:** برای استفاده از CKEditor باید در `@push('scripts')` این خط را اضافه کنید:

```blade
@include('new-admin._layout.blocks.utils.ckeditor-scripts')
```

### 6. x-form-check-box

کامپوننت چک‌باکس

```blade
<x-form-check-box 
    name="call_to_action" 
    label="نمایش دکمه تماس با کارشناسان" 
    :value="@$data" 
/>
```

### 7. x-form-text-area

کامپوننت متن چندخطی

```blade
<x-form-text-area 
    name="description" 
    label="توضیحات" 
    :value="@$data" 
/>
```

## CKEditor و Validation

### CKEditor

CKEditor به صورت خودکار در کامپوننت `x-form-ck-editor` فعال می‌شود. برای استفاده:

1. کامپوننت را در فرم استفاده کنید
2. در `@push('scripts')` این خط را اضافه کنید:

```blade
@include('new-admin._layout.blocks.utils.ckeditor-scripts')
```

**تنظیمات CKEditor:**
- زبان: فارسی
- آپلود تصویر: از طریق Route `admin.ckeditor.upload`
- دکمه‌های حذف شده: Font, PasteFromWord

### Validation

Validation ها به صورت Vue.js پیاده‌سازی شده‌اند:

**فایل‌های مورد نیاز:**
```blade
<script src="{{asset('assets/new-admin/js/vue.js')}}"></script>
<script src="{{asset('assets/new-admin/js/validations.js')}}"></script>
```

**Validation های موجود:**
- `requiredCms`: فیلد اجباری
- `urlCms`: فرمت URL
- سایر validation ها در فایل `validations.js` تعریف شده‌اند

**استفاده در فرم:**
```blade
<form id="cms-form" @submit.prevent="validateForm">
    <!-- فیلدها -->
</form>
```

## کامپوننت‌های کمکی

### Pagination

```blade
<div class="d-flex justify-content-center mt-3">
    {{ $data->links() }}
</div>
```

### Confirm Delete

```blade
@include('new-admin._layout.blocks.utils.confirmDelete')
```

استفاده:

```blade
<a onclick="confirmDelete('{{route('new-admin.blog.delete',['id'=>$row->id])}}')" href="#">
    حذف
</a>
```

### Copy Button

```blade
@include('new-admin.components.admin.copy-button', ['url' => $url])
```

## نکات مهم

1. **استفاده از Route Name**: همیشه از `route('admin.section.action')` استفاده کنید، نه URL مستقیم

2. **Layout**: همه صفحات باید از `@extends('new-admin._layout.master')` استفاده کنند

3. **Component Path**: کامپوننت‌ها از مسیر `new-admin.components` خوانده می‌شوند

4. **Validation**: برای validation از Vue.js استفاده می‌شود و باید `id="cms-form"` و `@submit.prevent="validateForm"` در فرم باشد

5. **CKEditor**: برای استفاده از CKEditor باید script مربوطه را در `@push('scripts')` اضافه کنید

6. **Image Cropper**: کامپوننت `x-form-image-input` به صورت خودکار cropper را فعال می‌کند

---

**تاریخ ایجاد مستند:** 2024  
**نسخه:** 1.0

