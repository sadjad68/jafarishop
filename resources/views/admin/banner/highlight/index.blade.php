@extends('admin._layouts.master')
@section('title','بنر ها')
@section('content')
<div class="body d-flex py-3">
    <div class="container-fluid">
        <div class="page-header">
            <div class="card-header py-3 no-bg bg-transparent border-0 px-0 flex-wrap">
                <h3 class="fw-bolder">
                    بنر ها (هایلایت صفحه اول)
                </h3>
                <div class="d-flex align-items-center justify-content-between w-100">
                    <a href="{{route('admin.highlight.create')}}"
                        class="btn ms-2 my-2 btn-custom-b rounded-custom d-flex align-items-center">
                        <i class="bi bi-plus-square-dotted d-flex h5 my-0 me-2"></i>
                        افزودن بنر
                    </a>
                    @component("admin.components.video-button")
                    @slot("type","highlights")
                    @endcomponent
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div role="alert" class="alert alert-info d-block small">
            <strong>جایگاه:</strong> برای هر اسلات (مثلاً بنر بالا-اولی) یک بنر ثبت کنید؛ همان تصویر در دسکتاپ و موبایل دیده می‌شود.
            <br>
            <strong>تگ:</strong> بنر زیر بلوک محصولات همان تگ در صفحه اول قرار می‌گیرد.
            <br>
            <span class="text-muted">برای بنرهای جایگاه، ردیف وقتی کامل دیده می‌شود که دست‌کم یکی از اسلات‌های آن تصویر داشته باشد.</span>
        </div>
        <div class="card-block row">
            <div class="col-sm-12 col-lg-12 col-xl-12">
                <form class="form-control">
                    <div class="table-responsive d-flex align-items-center">
                        <table id="myDataTable" class="table align-middle border-custom mb-0">
                            <thead class="text-center text-light">
                                <tr>
                                    <th class="fw-bolder">#</th>
                                    <th class="fw-bolder">تصویر</th>
                                    <th class="fw-bolder">نوع نمایش</th>
                                    <th class="fw-bolder">محل نمایش</th>
                                    <th class="fw-bolder">عنوان</th>
                                    <th class="fw-bolder">صفحه اول</th>
                                    <th class="fw-bolder">عملیات</th>
                                </tr>
                            </thead>
                            <tbody class="text-center text-light">
                                @foreach($banner as $key=>$row)
                                <tr>
                                    <th>{{ $key + ($banner->firstItem() ?? 1) }}</th>
                                    <th>
                                        <a target="_blank" href="{{ $row->image }}" rel="noopener">
                                            <img src="{{ $row->image }}" class="border shadow rounded"
                                                style="width:100px; max-height: 70px; object-fit: cover;"
                                                alt="{{ $row->title ?? '' }}"
                                                loading="lazy"
                                                onerror="this.onerror=null;this.src='{{ asset('assets/notfounds/default.jpg') }}';" />
                                        </a>
                                    </th>
                                    <th class="align-middle">
                                        @if($row->isTagTarget())
                                            <span class="badge bg-label-warning text-wrap lh-sm px-2 py-2 d-inline-block text-center"
                                                style="max-width: 10rem;">
                                                <i class="bi bi-tag-fill d-block mb-1 fs-5" aria-hidden="true"></i>
                                                بر اساس تگ
                                                <span class="d-block fw-normal mt-1 opacity-90" style="font-size: 10px;">بنر داخل بلوک همان تگ</span>
                                            </span>
                                        @else
                                            <span class="badge bg-label-success text-wrap lh-sm px-2 py-2 d-inline-block text-center"
                                                style="max-width: 10rem;">
                                                <i class="bi bi-columns-gap d-block mb-1 fs-5" aria-hidden="true"></i>
                                                بر اساس جایگاه
                                                <span class="d-block fw-normal mt-1 opacity-90" style="font-size: 10px;">چیدمان ثابت صفحهٔ اول</span>
                                            </span>
                                        @endif
                                    </th>
                                    <th class="text-start" style="min-width: 180px; max-width: 280px;">
                                        @if($row->isTagTarget())
                                            <div class="fw-bold">{{ $row->adminTagTitle() }}</div>
                                            <span class="small opacity-75">زیر بلوک محصولات همین تگ در صفحهٔ اول</span>
                                        @else
                                            <div class="fw-bold lh-sm">{{ $row->adminPlaceSlotTitleFromConfig() }}</div>
                                            <span class="small opacity-75 d-block mt-1">{{ $row->adminPlaceZoneDescription() }}</span>
                                        @endif
                                    </th>
                                    <th class="text-start small">{{ $row->title }}</th>
                                    <th>
                                        <span class="badge bg-label-{{$row->first_page_name['badge']}}"
                                            style="font-size: 11px">
                                            {{@$row->first_page_name['title']}}
                                        </span>
                                    </th>
                                    <th>
                                        <div class="btn-group" role="group">
                                            <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                data-bs-title="ویرایش"
                                                href="{{route('admin.highlight.edit',['id'=>$row->id])}}">
                                                <i class="d-flex bi bi-pencil-square color-custom2 fs-5"></i>
                                            </a>
                                            <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                data-bs-title="حذف"
                                                onclick="confirmDelete('{{route('admin.highlight.delete',['id'=>$row->id])}}')"
                                                href="#">
                                                <i class="d-flex bi bi-trash3 color-custom2 fs-5"></i>
                                            </a>
                                        </div>
                                    </th>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @component("admin.components.pagination.default")
                    @slot("paginator",$banner)
                    @endcomponent
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
@include('admin._layouts.blocks.utils.confirmDelete')
@endpush
