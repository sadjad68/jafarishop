@extends('admin._layouts.master')
@section('title',' اضافه کردن ویدیو و سوالات متداول به '.$service->title)
@section('content')
    <div class="body d-flex py-3">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="border-0 mb-4">
                        <div
                            class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                            <h3 class="fw-bolder mb-0">
                                سوالات متداول  {{$service->title}}
                            </h3>
                          @component("admin.components.back-button")
                        @endcomponent

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="card border-0 p-3">
                <form
                    action="{{route('admin.service-faq.create')}}"
                    method="POST"
                    enctype="multipart/form-data"
                    id="cms-form-video-faq"
                    @submit.prevent="validateForm"
                >
                    @csrf
                    @include('admin.service.service.faq.form')
                </form>
            </div>
        </div>
    </div>
@stop

