@extends('admin._layouts.master')
@section('title') متغییر های {{$product->title}}@stop
@section('content')
<div class="body d-flex py-3">
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="border-0 mb-4">
                    <div
                        class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                        <h3 class="fw-bolder mb-0">
                            متغییر های {{$product->title}}
                        </h3>

                        <div class="d-flex align-items-center gap-3">
                            @component("admin.components.video-button")
                                @slot("type","product_variables")
                            @endcomponent
                               @component("admin.components.back-button")
                        @endcomponent
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">

        <div class="card border p-2" id="spf-elements">
{{--            @if(count($product->main_specifications) > 0)--}}
                <form action="{{ route('admin.product-variant.create') }}" method="POST" enctype="multipart/form-data" @submit.prevent="submitForm">
                    @csrf
                    @include('admin.product.product.variant.form')
                </form>
{{--                @else--}}
{{--                <div class="text-dark justify-content-center text-center">--}}
{{--                    <p>--}}
{{--                        ابتدا متغییر اصلی {{@$product->title}} را مشخص کنید--}}
{{--                    </p>--}}
{{--                </div>--}}

{{--                    @endif--}}
        </div>
    </div>
</div>
@stop
