@extends('admin._layouts.master')

@section('title')
مرتب سازی خدمات
@stop

@section('content')
@php
    $backUrl = isset($data)
        ? ($data->parent_id != null
            ? route('admin.service.sort', ['parent_id' => $data->parent_id])
            : route('admin.service.sort'))
        : route('admin.service.index');
@endphp

<div class="body d-flex py-3" id="cms-form">
    <div class="container-fluid">
        <div class="page-header">
            <div class="card-header py-3 no-bg bg-transparent border-0 px-0 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h3 class="fw-bolder mb-0">
                    مرتب سازی خدمات {{ @$data->title }}
                </h3>
                <a href="{{ $backUrl }}" class="btn btn-custom rounded-custom w-fit px-3 py-2">
                    <i class="bi bi-arrow-right-short me-1"></i>
                    بازگشت
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="card-block row">
            <div class="col-12">
                @include('admin.components.sort-panel', [
                    'items' => $services,
                    'sortUrl' => route('admin.service.sort'),
                    'childrenRoute' => 'admin.service.sort',
                ])
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/admin/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/admin-sortable.js') }}"></script>
    <script>
        $(function () {
            AdminSortable.init('#admin-sort-list');
        });
    </script>
@endpush
