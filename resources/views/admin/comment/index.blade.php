@extends('admin._layouts.master')
@section('title','نظرات')
@section('content')
    <div class="body d-flex py-3">
        <div class="container-fluid">
            <div class="page-header">
                <div
                    class="card-header py-3 no-bg bg-transparent border-0 px-0 flex-wrap d-flex justify-content-between align-items-center">
                    <h3 class="fw-bolder">
                        نظرات
                    </h3>
{{--                    <div class="d-flex align-items-center">--}}
{{--                        <a id="myBtn" data-bs-target="#myModal" data-bs-toggle="modal"--}}
{{--                           class="btn ms-2 my-2 btn-custom rounded-custom d-flex align-items-center">--}}
{{--                            <i class="bi bi-search d-flex h5 my-0 me-2"></i>--}}
{{--                            جستجوی پیشرفته--}}
{{--                        </a>--}}
{{--                    </div>--}}
                    <ul class="list-inline align-items-center m-0">
                        <li class="list-inline-item mx-0">
                            <a data-bs-target="#searchModal" data-bs-toggle="modal"
                               class="btn my-2 btn-custom rounded-custom d-flex align-items-center">
                                <i class="bi bi-search d-flex my-0 me-2"></i>
                                جستجوی پیشرفته
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="card-block row">
                <div class="col-sm-12 col-lg-12 col-xl-12">
                    <form class="form-control">
                        <div class="table-responsive d-flex align-items-center">
                            <table id="myDataTable" class="table align-middle border-custom mb-0">
                                <thead class="text-center text-light">
                                <tr>
                                    <th class="fw-bolder">
                                        #
                                    </th>
                                    <th class="fw-bolder">
                                        فرستنده
                                    </th>
                                    <th class="fw-bolder">
                                        شماره همراه
                                    </th>
                                    <th class="fw-bolder">
                                        نوع
                                    </th>
                                    <th class="fw-bolder">
                                        ارسال شده در بخش
                                    </th>
                                    <th class="fw-bolder">
                                        تعداد ستاره
                                    </th>
                                    <th class="fw-bolder">
                                        تاریخ
                                    </th>
                                    <th class="fw-bolder">
                                        نمایش در صفحه مربوطه
                                    </th>
                                    <th class="fw-bolder" style="width: 20%">
                                        عملیات
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="text-center text-light">
                                @foreach($comment as $key=>$row)
                                    <tr>
                                        <th>
                                            {{$key + 1}}
                                        </th>

                                        <th>
                                        <span style="font-size: 11px">
                                            {{$row->name}}
                                            {{ ($row->user_id && $row->user) ? '('. $row->user->full_name. ')' : ''}}
                                        </span>
                                        </th>
                                        <th>
                                        <span style="font-size: 11px">
                                            {{$row->mobile}}
                                        </span>
                                        </th>
                                        <th>
                                        <span style="font-size: 11px">
                                            {{ $row->reply_id != null ? 'پاسخ به کامنت ' . (optional($row->comment)->name ?? '—') : 'کامنت اصلی' }}
                                        </span>
                                        </th>
                                        <th>
                                            <a href="{{ $row->comment_url ?: '#' }}" target="_blank">
                                                <span style="font-size: 11px">
                                                    {{ class_exists($row->commentable_type) ? $row->model_name . ' | ' . (optional($row->commentable)->title ?? '—') : '—' }}
                                                </span>
                                            </a>
                                        </th>
                                        <th>
                                        <span style="font-size: 11px">
                                            {{$row->rate}}
                                        </span>
                                        </th>
                                        <th>
                                        <span style="font-size: 11px">
                                            {{@$row->date}}
                                        </span>
                                        </th>
                                        <th>
                                        <span class="badge bg-label-{{$row->status_item['badge']}}"
                                              style="font-size: 11px">
                                            {{@$row->status_item['title']}}
                                        </span>
                                        </th>
                                        <th>
                                            <div class="btn-group" role="group">
                                                <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                   data-bs-title="ویرایش"
                                                   href="{{url('admin/comment/edit/'.$row['id'])}}">
                                                    <i class="d-flex bi bi-pencil-square color-custom2 fs-5"></i>
                                                </a>
                                                <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                   data-bs-title="حذف"
                                                   onclick="confirmDelete('/admin/comment/delete/{{ @$row['id'] }}')"
                                                   href="#">
                                                    <i class="d-flex bi bi-trash3 color-custom2 fs-5"></i>
                                                </a>
                                                @if($row['reply_id'] == null)
                                                    <button type="button"
                                                            class="btn me-2 p-0 bg-transparent border-0 shadow-none"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#exampleModal{{$row['id']}}"
                                                            data-bs-title="پاسخ"
                                                            title="پاسخ">
                                                <span data-bs-toggle="tooltip" data-bs-title="پاسخ">
                                                    <i class="bi bi-reply d-flex color-custom2 fs-5"></i>
                                                </span>
                                                    </button>
                                                @endif
                                                @include('admin.comment.reply-modal')                                                @if($row['status'] == 1)
                                                    <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                       data-bs-title="غیر فعال کردن"
                                                       href="{{url('admin/comment/edit-status/'.$row['id'])}}">
                                                        <i class="d-flex bi bi-x-square color-custom2 fs-5"></i>
                                                    </a>
                                                @else
                                                    <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                       data-bs-title="فعال کردن"
                                                       href="{{url('admin/comment/edit-status/'.$row['id'])}}">
                                                        <i class="d-flex bi bi-check-square color-custom2 fs-5"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </th>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if(count($comment))
                            @include('admin.components.pagination.default',['paginator'=>$comment])
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- search modal --}}
    <div id="searchModal" class="modal fade" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-custom border-custom shadow bg-white">

                <div class="modal-header px-3 py-2">
                    <h4 class="m-0">
                        جستجو در نظرات
                    </h4>
                    <button type="button" class="close btn px-0" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <div class="modal-body p-2">

                    <form method="GET" action="{{URL::current()}}">
                        <input type="hidden" name="filter">

                        <div class="row m-0">

                            <div class="col-lg-4 p-2">
                                <x-cms-input
                                    name="name"
                                    label="نام فرستنده"
                                    type="text"
                                />
                            </div>

                            <div class="col-lg-4 p-2">
                                <x-cms-input
                                    name="mobile"
                                    label="شماره موبایل"
                                    type="text"
                                />
                            </div>

                            <div class="col-lg-4 p-2">
                                <div class="form-group">
                                    <label>وضعیت</label>
                                    <select name="status" class="form-select bg-light rounded-custom">
                                        <option value="">همه</option>
                                        <option value="1">منتشر شده</option>
                                        <option value="0">عدم انتشار</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-4 p-2">
                                <div class="form-group">
                                    <label>نوع محتوا</label>
                                    <select name="commentable_type" class="form-select bg-light rounded-custom">
                                        <option value="">همه</option>
                                        <option value="{{ \App\Modules\Blog\Entities\Blog::class }}">مطلب</option>
                                        <option value="{{ \App\Modules\Product\Entities\Product::class }}">محصول</option>
                                        <option value="{{ \App\Modules\Service\Entities\Service::class }}">خدمات</option>
                                        <option value="{{ \App\Modules\Service\Entities\WorkSample::class }}">نمونه کار</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-4 p-2">
                                <div class="form-group">
                                    <label>امتیاز</label>
                                    <select name="rate" class="form-select bg-light rounded-custom">
                                        <option value="">همه</option>
                                        <option value="5">5 ستاره</option>
                                        <option value="4">4 ستاره</option>
                                        <option value="3">3 ستاره</option>
                                        <option value="2">2 ستاره</option>
                                        <option value="1">1 ستاره</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-4 p-2">
                                <div class="form-group">
                                    <label>نوع کامنت</label>
                                    <select name="reply_id" class="form-select bg-light rounded-custom">
                                        <option value="">همه</option>
                                        <option value="null">کامنت اصلی</option>
                                        <option value="not_null">پاسخ</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-xxl-6 col-sm-8 p-2">
                                <div class="form-group">
                                    <label>از تاریخ</label>
                                    <input class="form-control bg-light rounded-custom"
                                           type="text"
                                           id="datepicker1"
                                           name="publish_date_start"
                                           placeholder="از تاریخ"
                                           value="{{ request('publish_date_start') }}">
                                </div>
                            </div>

                            <div class="col-xxl-6 col-sm-8 p-2">
                                <div class="form-group">
                                    <label>تا تاریخ</label>
                                    <input class="form-control bg-light rounded-custom"
                                           type="text"
                                           id="datepicker2"
                                           name="publish_date_end"
                                           placeholder="تا تاریخ"
                                           value="{{ request('publish_date_end') }}">
                                </div>
                            </div>


                            <div class="col-12 p-2">
                                <button type="submit" class="btn btn-custom rounded-custom w-100">
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

    {{--    <div id="myModal" class="modal fade" style="display: none;" aria-hidden="true">--}}
{{--        <div class="modal-dialog modal-dialog-scrollable">--}}
{{--            <div class="modal-content rounded-custom border-custom shadow bg-white">--}}
{{--                <div class="modal-header px-3 py-2">--}}
{{--                    <h4 class="m-0">--}}
{{--                        جستجو--}}
{{--                    </h4>--}}
{{--                    <button type="button" class="close btn px-0" data-bs-dismiss="modal" aria-label="Close">--}}
{{--                        <i class="bi bi-x-lg d-flex" aria-hidden="true"></i>--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--                <div class="modal-body p-2">--}}
{{--                    <form id="cms-form" method="GET" action="{{URL::current()}}" class="m-0">--}}
{{--                        <div class="px-2">--}}

{{--                            <div class="form-group p-0">--}}
{{--                                <label>--}}
{{--                                    عنوان--}}
{{--                                </label>--}}
{{--                                <input name="name" id="" class="form-control rounded-custom">--}}
{{--                            </div>--}}


{{--                            <button type="submit" id="submitFormCms"--}}
{{--                                    class="btn btn-custom rounded-custom w-fit ms-auto mt-3">--}}
{{--                                <i class="bi bi-search"></i>--}}
{{--                                جستجو--}}
{{--                            </button>--}}
{{--                        </div>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
    @stack('modals')
@endsection
@push('scripts')
    <script src="{{asset('assets/admin/js/vue.js')}}"></script>
    <script src="{{asset('assets/admin/js/validations.js')}}"></script>
    @include('admin._layouts.blocks.utils.confirmDelete')

    <script src="{{ asset('assets/admin/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/bootstrap-datepicker.fa.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $("#datepicker1").datepicker({
                changeMonth: true,
                changeYear: true,
                format: "dd/mm/yyyy",
                language: "fa",
                autoclose: true
            });
            $("#datepicker2").datepicker({
                changeMonth: true,
                changeYear: true,
                format: "dd/mm/yyyy",
                language: "fa",
                autoclose: true
            });
        });
    </script>

@endpush
