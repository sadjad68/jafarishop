@extends('admin._layouts.master')

@section('title','ویرایش تم ها')
@section('content')
<div class="body d-flex py-3">
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="border-0 mb-4">
                    <div
                        class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                        <h3 class="fw-bolder mb-0">
                            ویرایش تم ها
                        </h3>
                        <div class="d-flex align-items-center justify-content-between w-100"
                            style="flex-direction: row-reverse;">

                            <ul class="list-inline align-items-center m-0">
                                <li class="list-inline-item mx-0">
                                    <a href="{{'/purge-cache'}}" target="_blank"
                                        class="btn ms-2 my-2 btn-custom-b rounded-custom d-flex align-items-center">
                                        <i class="bi bi-trash2 d-flex h5 my-0 me-2"></i>
                                        پاک کردن کش سایت
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">


        <div class="card border p-2">
            <form action="{{route('admin.theme.edit')}}" method="POST" enctype="multipart/form-data" id="cms-form"
                @submit.prevent="validateForm">
                @csrf
                @include('admin.setting.theme.form')
            </form>
        </div>
    </div>
</div>
@stop
