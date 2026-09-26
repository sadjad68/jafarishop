@extends('admin._layouts.master')

@section('title') ویرایش سایت مپ @stop
@section('content')
<div class="body d-flex py-3">
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="border-0 mb-4">
                    <div
                        class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                        <h3 class="fw-bolder mb-0">
                           سایت مپ
                        </h3>
                        <div class="d-flex flex-md-row flex-column align-items-md-center justify-content-md-between w-100">
                            @component("admin.components.video-button")
                                @slot("type","seo_settings")
                            @endcomponent
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="card border p-2">
            <form action="{{route('admin.sitemap.edit')}}" method="POST" enctype="multipart/form-data"
                id="cms-form" @submit.prevent="validateForm">
                @csrf
                @include('admin.setting.sitemap.form')
            </form>
        </div>
    </div>
</div>
@stop
@push('scripts')
<script src="{{asset('assets/admin/js/vue.js')}}"></script>
<script src="{{asset('assets/admin/js/validations.js')}}"></script>
@endpush
