@extends('admin._layouts.master')

@section('title') افزودن مطلب @stop
@section('content')
    <div class="body d-flex py-3">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="border-0 mb-4">
                        <div
                            class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                            <h3 class="fw-bolder mb-0">
                                افزودن مطلب
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<div class="container-fluid">
    <div class="card border p-2">
        <form
            action="{{route('admin.blog.test-create')}}"
            method="POST"
            enctype="multipart/form-data"
            id="cms-form"
            @submit.prevent="validateForm"
        >
            @csrf
            <div class="col-xxl-3 col-sm-6 p-2">
                <x-cms-image-input
                    name="image"
                    label="تصویر (سایز : w450 * h450 )"
                    :imageSrc="(isset($data) && $data->image) ? $data->getItemImage() : null"
                    :validations="['requiredCms']"
                    width="1000"
                    height="1000"
                    cropper="1"
                />
            </div>
            <div class="w-100 pe-0">
                <button type="submit" id="submitFormCms" class="btn btn-custom rounded-custom w-fit px-3 py-2">
                    ذخیره
                </button>
            </div>
        </form>
    </div>
</div>
    </div>
@stop
@push('scripts')
    <script src="{{asset('assets/admin/js/vue.js')}}"></script>
    <script src="{{asset('assets/admin/js/validations.js')}}"></script>
@endpush
