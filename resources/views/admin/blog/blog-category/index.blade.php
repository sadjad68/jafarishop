@extends('admin._layouts.master')

@section('title')
    دسته بندی مطالب
@stop
@section('content')
    <div class="body d-flex py-3" id="cms-form">
        <div class="container-fluid">
            <div class="page-header">
                <div
                    class="card-header py-3 no-bg bg-transparent border-0 px-0 flex-wrap">
                    <h3 class="fw-bolder mb-0">
                        دسته بندی مطالب
                    </h3>
                    <div class="d-flex flex-md-row flex-column align-items-md-center justify-content-md-between w-100">
                        <a href="{{route('admin.blog-category.create')}}"
                           class="btn ms-2 my-2 btn-custom-b rounded-custom d-flex align-items-center w-fit">
                            <i class="bi bi-plus-square-dotted d-flex h5 my-0 me-2"></i>
                            افزودن دسته بندی مطالب
                        </a>
                        @component("admin.components.video-button")
                            @slot("type","posts")
                        @endcomponent
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="card-block row">
                <div class="col-sm-12 col-lg-12 col-xl-12">
                    <form class="form-control">
                        <div class="table-responsive d-flex align-items-center">
                            <table id="myDataTable"
                                   class="table align-middle border-custom mb-0">
                                <thead class="text-center text-light">
                                <tr>
                                    {{--                                <th>--}}
                                    {{--                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">--}}
                                    {{--                                </th>--}}
                                    <th class="fw-bolder">
                                        #
                                    </th>
                                    <th class="fw-bolder">
                                        عنوان
                                    </th>
                                    <th class="fw-bolder" >
                                        تصویر
                                    </th>
                                    <th class="fw-bolder" >
                                        دسته بندی
                                    </th>
                                    <th class="fw-bolder" >
                                 وضعیت
                                    </th>
                                    <th class="fw-bolder" >
                                        تاریخ
                                    </th>
                                    <th class="fw-bolder" >
                                        عملیات
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="text-center text-light">
                                @foreach($blog_category as $key=>$row)
                                    <tr>
                                        {{--                                    <th>--}}
                                        {{--                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">--}}
                                        {{--                                    </th>--}}
                                        <th>
                                            {{$key + 1}}
                                        </th>
                                        <th>
                                        <span style="font-size: 14px">
                                            {{$row['title']}}
                                        </span>
                                        </th>
                                        <th>
                                            <img
                                                src="{{@$row->item_image}}"
                                                class="border shadow rounded" style="width:50px">
                                        </th>
                                        <th>
                                        <span class="badge bg-label-primary">
                                             {{$row->parent_id ? @$row->parent->title : "اصلی"}}
                                        </span>
                                        </th>
                                        <th>
                                        <span class="badge bg-label-{{$row->status_name['badge']}}"
                                              style="font-size: 11px">
                                            {{@$row->status_name['title']}}
                                        </span>
                                        </th>
                                        <th>
                                            {{@$row->date}}
                                        </th>
                                        <th>

                                            <div class="btn-group" role="group">
                                                @include('admin.seo.seo.form', ['data' => $row])
                                                <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip" data-bs-title="ویرایش"
                                                   href="{{route('admin.blog-category.edit',['id'=>$row->id])}}">
                                                    <i class="d-flex bi bi-pencil-square color-custom2 fs-5"></i>
                                                </a>
                                                <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip" data-bs-title="حذف"
                                                   onclick="confirmDelete('{{route('admin.blog-category.delete',['id'=>$row->id])}}')"
                                                   href="#">
                                                    <i class="d-flex bi bi-trash3 color-custom2 fs-5"></i>
                                                </a>
                                                <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                   data-bs-title="مشاهده" target="_blank"
                                                   href="{{ route('blog.list', ['url' => $row['url']]) }}">
                                                    <i class="d-flex bi bi-eye color-custom2 fs-5"></i>
                                                </a>
                                                @include('admin.components.admin.copy-button', ['url' => parse_url(route('blog.list', ['url' => $row['url']]), PHP_URL_PATH)])

                                            </div>
                                        </th>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        @component("admin.components.pagination.default")
                            @slot("paginator",$blog_category)
                        @endcomponent
                    </form>
                </div>
            </div>
        </div>
        @stack('modals')
    </div>
    {{--  search modal  --}}
    <div id="myModal" class="modal fade" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content rounded-custom border-custom shadow bg-white">
                <div class="modal-header px-3 py-2">
                    <h4 class="m-0">
                        جستجو
                    </h4>
                    <button type="button" class="close btn px-0" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg d-flex" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="modal-body p-2">
                    <form id="cms-form" method="GET" action="{{URL::current()}}" class="m-0">
                        <div class="row w-100 m-0">
                            <div class="col-lg-4 p-2">
                                <div class="form-group">
                                    <label>
                                        عنوان
                                    </label>
                                    <input name="title" id="" class="form-control rounded-custom">
                                </div>
                            </div>
                            <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-7 col-sm-10 col-12 ms-auto p-2">
                                <button type="submit"  id="submitFormCms" class="btn btn-success rounded-custom w-100">
                                    <i class="bi bi-search"></i>
                                    جستجو
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{asset('assets/admin/js/vue.js')}}"></script>
    <script src="{{asset('assets/admin/js/validations.js')}}"></script>
    @include('admin._layouts.blocks.utils.confirmDelete')
@endpush
