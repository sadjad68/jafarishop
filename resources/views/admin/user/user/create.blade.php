@extends('admin._layouts.master')
@section('title',"افزودن کاربر جدید")
@section('content')
<div class="body d-flex py-3">
    <div class="container-fluid">
        <div class="page-header">
            <div
                class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-0 flex-wrap">
                <h3 class="fw-bolder mb-0">
                    افزودن کاربر جدید
                </h3>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="card border p-2">
            <form action="{{route('admin.user.create')}}" method="POST" enctype="multipart/form-data" id="cms-form"
                @submit.prevent="validateForm">
                @csrf
                @include('admin.user.user.form')
            </form>
        </div>
    </div>
</div>
@stop
@push('scripts')
<script src="{{asset('assets/admin/js/vue.js')}}"></script>
<script src="{{asset('assets/admin/js/validations.js')}}"></script>
@endpush
