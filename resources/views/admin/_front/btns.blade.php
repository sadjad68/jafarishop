@extends('admin._layouts.master')
@section('title','داشبورد')
@section('content')
<ul class="list-inline align-items-center m-0">
    <li class="list-inline-item mx-0">
        <a href=""
           class="btn my-2 btn-custom rounded-custom d-flex align-items-center">
            <i class="bi bi-file-earmark-spreadsheet d-flex my-0 me-2"></i>
            خروجی اکسل
        </a>
    </li>
    <li class="list-inline-item mx-0">
        <a href=""
           class="btn my-2 btn-custom rounded-custom d-flex align-items-center">
            <i class="bi bi-trash3 d-flex my-0 me-2"></i>
            حذف همه
        </a>
    </li>
    <li class="list-inline-item mx-0">
        <a id="myBtn" data-bs-target="#myModal" data-bs-toggle="modal"
           class="btn my-2 btn-custom rounded-custom d-flex align-items-center">
            <i class="bi bi-search d-flex my-0 me-2"></i>
            جستجوی پیشرفته
        </a>
    </li>
</ul>

@endsection
