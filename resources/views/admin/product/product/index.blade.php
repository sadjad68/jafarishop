@extends('admin._layouts.master')
@section('title', 'محصولات')
@section('content')
    <div class="body d-flex py-3" id="cms-form">
        <div class="container-fluid">
            <div class="page-header">
                <div class="card-header py-3 no-bg bg-transparent border-0 px-0 flex-wrap">
                    <h3 class="fw-bolder mb-0">
                        محصولات
                    </h3>
                    <div class="d-flex flex-md-row flex-column align-items-md-center justify-content-md-between w-100">
                        <a href="{{ route('admin.product.create') }}"
                            class="btn ms-2 my-2 btn-custom-b rounded-custom d-flex align-items-center w-fit">
                            <i class="bi bi-plus-square-dotted d-flex h5 my-0 me-2"></i>
                            افزودن محصول
                        </a>
                        @if(Route::has('admin.product.import.index'))
                        <a href="{{ route('admin.product.import.index') }}"
                            class="btn ms-2 my-2 btn-custom-b rounded-custom d-flex align-items-center w-fit">
                            <i class="bi bi-upload d-flex my-0 me-2"></i>
                            ورود گروهی محصولات
                        </a>
                        @endif
                        <a data-bs-target="#excelModal" data-bs-toggle="modal"
                            class="btn ms-2 my-2 btn-custom-b rounded-custom d-flex align-items-center w-fit">
                            <i class="bi bi-file-earmark-excel-fill d-flex my-0 me-2"></i>
                            آپدیت موجودی و قیمت
                        </a>
                        <ul class="list-inline align-items-center m-0">
                            <li class="list-inline-item mx-0">
                                <a data-bs-target="#searchModal" data-bs-toggle="modal"
                                    class="btn my-2 btn-custom rounded-custom d-flex align-items-center">
                                    <i class="bi bi-search d-flex my-0 me-2"></i>
                                    جستجوی پیشرفته
                                </a>
                            </li>
                        </ul>
                        <div class="d-flex align-items-center">
                            @component('admin.components.video-button')
                                @slot('type', 'product')
                            @endcomponent
                            @component('admin.components.video-button')
                                @slot('text', 'محصولات شگفت انگیز')
                                @slot('type', 'product_timer')
                            @endcomponent
                        </div>

                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <div class="card-block row">
                    <div class="col-sm-12 col-lg-12 col-xl-12">
                        <div class="form-control">
                            <div class="table-responsive d-flex align-items-center">
                                <table id="myDataTable" class="table align-middle border-custom mb-0">
                                    <thead class="text-center text-light">
                                        <tr>
                                            <th class="fw-bolder">
                                                #
                                            </th>
                                            <th class="fw-bolder">
                                                عنوان
                                            </th>
                                            <th class="fw-bolder" style="width: 10%;">
                                                تصویر
                                            </th>
                                            <th class="fw-bolder" style="width:100px">
                                                برند
                                            </th>
                                            <th class="fw-bolder" style="width: 5cm">
                                                دسته
                                            </th>
                                            <th class="fw-bolder" style="width: 2cm">
                                                وضعیت نمایش
                                            </th>
                                            <th class="fw-bolder" style="width: 5cm">
                                                تاریخ
                                            </th>
                                            <th class="fw-bolder" style="width:250px">
                                                عملیات
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center text-light">
                                        @foreach ($product as $key => $row)
                                            <tr>
                                                <th>
                                                    {{ $key + 1 }}
                                                </th>
                                                <th style="white-space:normal">
                                                    <span style="font-size: 11px">
                                                        {{ $row['title'] }}
                                                    </span>
                                                </th>
                                                <th>
                                                    <img src="{{ @$row->getImage() }}" class="border shadow rounded" style="width:50px">
                                                </th>
                                                <th>
                                                    <span class="badge bg-label-primary">
                                                        {{ @$row->brand->title }}
                                                    </span>
                                                </th>
                                                <th style="width: 5cm;white-space:normal">
                                                    @if ($row->categories?->isNotEmpty())
                                                        <x-admin.see-more-btn :data="$row->categories" :row="$row" />
                                                    @endif
                                                </th>
                                                <th style="width: 2cm;white-space:normal">
                                                    <span class="badge m-1 bg-label-{{ $row->active_name['badge'] }}" style="font-size: 11px">
                                                        {{ @$row->active_name['title'] }}
                                                    </span>
                                                    <span class="badge m-1 bg-label-{{ $row->first_page_name['badge'] }}" style="font-size: 11px">
                                                        {{ @$row->first_page_name['title'] }}
                                                    </span>
                                                    @if($row->price_balancing != null)
                                                        <span class="badge m-1 bg-label-warning" style="font-size: 11px">
                                                         {{config('site.price_balancing')[@$row->price_balancing] }}
                                                        </span>
                                                    @endif
                                                    {{--   <span class="badge bg-label-{{$row->stock_product['badge']}}" --}}
                                                    {{--   style="font-size: 11px"> --}}
                                                    {{--   {{@$row->stock_product['title']}} --}}
                                                    {{--   </span> --}}
                                                </th>
                                                <th style="width: 5cm;white-space:normal">
                                                    {{ @$row->date }}
                                                </th>
                                                <th>
                                                    <div class="btn-group" role="group">
                                                        <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                            data-bs-title="ویرایش"
                                                            href="{{ route('admin.product.edit', ['id' => $row->id]) }}">
                                                            <i class="d-flex bi bi-pencil-square color-custom2 fs-5"></i>
                                                        </a>

                                                        <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                            data-bs-title="حذف"
                                                            onclick="confirmDelete('{{ route('admin.product.delete', ['id' => $row->id]) }}')"
                                                            href="#">
                                                            <i class="d-flex bi bi-trash3 color-custom2 fs-5"></i>
                                                        </a>
                                                        <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                            data-bs-title="مشاهده" target="_blank"
                                                            href="{{ \App\Library\SiteUrl::product($row) }}">
                                                            <i class="d-flex bi bi-eye color-custom2 fs-5"></i>
                                                        </a>
                                                        <button type="button"
                                                        class="d-flex me-2 align-items-center btn btn-primary boorder-0 p-1 px-2 shadow-none rounded-3 small-11"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#exampleModalBy{{ $key }}">
                                                        <span>
                                                            سایر
                                                        </span>
                                                    </button>
                                                    </div>
                                                </th>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @component('admin.components.pagination.default')
                                @slot('paginator', $product)
                            @endcomponent
                        </div>
                    </div>
                </div>
            </div>
            @push('modals')
                @foreach ($product as $key => $row)
                    <div class="modal fade" id="exampleModalBy{{ $key }}" tabindex="-1"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <p class="modal-title small" id="exampleModalLabel">سایر عملیات ها</p>
                                    @if (@$row->creator_id && $row->creator)
                                        <p class="text-muted small mt-1 ms-3 mb-0">
                                            <i class="bi bi-person-circle me-1"></i>
                                            سازنده:
                                            <span class="fw-medium text-dark">{{ @$row->creator->full_name }}</span>
                                        </p>
                                    @endif
                                    <button type="button" class="btn-close m-0 me-auto shadow-none" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="d-flex flex-wrap gap-2 align-items-center justify-content-center">
                                        <a class="d-flex align-items-center btn-outline-primary btn gap-1"
                                            href="{{ route('admin.product-image.index', ['id' => $row->id]) }}">
                                            <i class="d-flex bi bi-images fs-5"></i>
                                            تصاویر
                                        </a>
                                        <a class="d-flex align-items-center btn btn-outline-primary gap-1"
                                            href="{{ route('admin.product-variant.index', ['id' => $row->id]) }}">
                                            <i class="d-flex bi bi-cash-coin fs-5"></i>
                                            متغییر ها
                                        </a>
                                        @include('admin.seo.seo.form', ['data' => $row])
                                        <a class="d-flex align-items-center btn-outline-primary btn gap-1"
                                            href="{{ route('admin.product-video-faq.index', ['id' => $row->id]) }}">
                                            <i class="d-flex bi bi-camera-video fs-5"></i>
                                            ویدیو/سوالات متداول
                                        </a>
                                        <a class="d-flex align-items-center btn gap-1 btn-outline-primary"
                                            href="{{ route('admin.product-property-spf-tag.index', ['id' => $row->id]) }}">
                                            <i class="d-flex bi bi-tags fs-5"></i>
                                            مشخصه/ویژگی/فیلتر
                                        </a>
                                        @if ($row['discounted_price'] != null)
                                            <button type="button"
                                                class="btn shadow-none btn-outline-primary d-flex align-items-center gap-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#exampleModal{{ $row['id'] }}"
                                                title="تایمر شگفت انگیز">
                                                <span class="d-flex align-items-center gap-1" data-bs-toggle="tooltip"
                                                    data-bs-title="تایمر شگفت انگیز">
                                                    <i class="bi bi-clock d-flex fs-5"></i>
                                                    تایمر شگفت انگیز
                                                </span>
                                            </button>
                                        @endif
                                        <x-admin.copy-button :url="parse_url(\App\Library\SiteUrl::product($row), PHP_URL_PATH)" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                @include('admin.product.product.modal')
            @endpush
            @stack('modals')
        </div>
        @include('admin.product.product.search')
        @include('admin.product.product.excel')
    @endsection
    @push('scripts')
        <script src="{{ asset('assets/admin/js/vue.js') }}"></script>
        <script src="{{ asset('assets/admin/js/validations.js') }}"></script>
        @include('admin._layouts.blocks.utils.confirmDelete')
        <script>
            document.getElementById('productIndexSeoModal')?.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button?.getAttribute('data-seo-id');
                const input = document.getElementById('productIndexSeoableId');
                if (id && input) {
                    input.value = id;
                }
            });
        </script>
    @endpush
