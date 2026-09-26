@extends('admin._layouts.master')

@section('title') افزودن دسته بندی محصول @stop
@section('content')
<div class="body d-flex py-3">
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="border-0 mb-4">
                    <div
                        class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                        <h3 class="fw-bolder mb-0">
                            افزودن دسته بندی محصول
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="card border p-2">
            <form
                action="{{route('admin.product-category.create')}}"
                method="POST"
                enctype="multipart/form-data"
                id="cms-form"
                @submit.prevent="validateForm"
            >
                @csrf
                @include('admin.product.product-category.form')
            </form>
        </div>
    </div>
</div>
@stop
@push('scripts')
    <script src="{{asset('assets/admin/js/vue.js')}}"></script>
    <script src="{{asset('assets/admin/js/validations.js')}}"></script>
@endpush
