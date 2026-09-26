@extends('admin._layouts.master')

@section('title')
    جلسات
@stop
@section('content')
    <div class="body d-flex py-3">
        <div class="container-fluid">
            <div class="page-header">
                        <div
                            class="card-header py-3 no-bg bg-transparent border-0 px-0 flex-wrap">
                            <h3 class="fw-bolder">
                                جلسات{{@$corse ? @$corse->title : ''}}
                            </h3>
                            <div class="d-flex flex-md-row flex-column align-items-md-center justify-content-md-between w-100">
                                @if(! isset($corse) || $corse == null)
                                    <a href="{{url('admin/session/create')}}"
                                    class="btn ms-2 my-2 btn-custom-b rounded-custom d-flex align-items-center w-fit">
                                        <i class="bi bi-plus-square-dotted d-flex h5 my-0 me-2"></i>
                                        افزودن جلسه
                                    </a>
                                @else
                                    <a href="{{url('admin/session/create?course_id='.$corse->id)}}"
                                    class="btn ms-2 my-2 btn-custom-b rounded-custom d-flex align-items-center w-fit">
                                        <i class="bi bi-plus-square-dotted d-flex h5 my-0 me-2"></i>
                                        افزودن جلسه
                                    </a>
                                @endif
                            </div>
                        </div>
            </div>
        </div>
        @if(isset($corse) && $corse != null)
            <div class="container-fluid">
                <p>
                    راهنما : می توانید با کشیدن و رها کردن هر سطر ترتیب نمایش جلسات این دوره به کاربران را تغییر دهید
                </p>
            </div>
        @endif
        <div class="container-fluid">
            <div class="card-block row">

                <div class="col">
                    <form class="form-control">
                    <div class="table-responsive d-flex align-items-center">
                        <table id="myDataTable"
                               class="table align-middle border-custom mb-0">
                            <thead class="text-center text-light">
                            <tr>
                                <th>
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                </th>
                                <th class="fw-bolder">
                                    #
                                </th>
                                <th class="fw-bolder">
                                    عنوان
                                </th>
                                <th class="fw-bolder" style="width: 10%;">
                                    تصویر
                                </th>
                                <th class="fw-bolder" >
                                    وضعیت
                                </th>
                                @if(! isset($corse) || $corse == null)
                                <th class="fw-bolder" >
                                    دوره
                                </th>
                                @endif
                                <th class="fw-bolder" >
                                    تاریخ
                                </th>
                                <th class="fw-bolder" style="width:250px">
                                    عملیات
                                </th>
                            </tr>
                            </thead>
                            <tbody class="text-center text-light">
                            @foreach($session as $key=>$row)
                                <tr data-id="{{ $row['id'] }}">
                                    <th>
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
                                    </th>
                                    <th>
                                        {{$key + 1}}
                                    </th>
                                    <th>
                                        <span style="font-size: 11px">
                                            {{$row['title']}}
                                        </span>
                                    </th>
                                    <th>
                                        <img
                                            src="{{@$row->item_image}}"
                                            class="border shadow rounded" style="width:50px">
                                    </th>

                                    <th>
                                        @if(@$row->active == 1)
                                            <span class="badge  bg-label-success" style="font-size: 11px">
                                          فعال
                                        </span>
                                        @else
                                            <span class="badge  bg-label-danger" style="font-size: 11px">
                                                غیر فعال
                                        </span>
                                        @endif
                                    </th>
                                    @if(! isset($corse) || $corse == null)
                                    <th>
                                         <span class="badge  bg-label-primary" style="font-size: 11px">
                                        {{@$row->course->title}}
                                         </span>
                                    </th>
                                    @endif
                                    <th>
                                        {{@$row->date}}
                                    </th>
                                    <th>
                                        <div class="btn-group" role="group">
                                        @include('admin.seo.seo.form', ['data' => $row])
                                                <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip" data-bs-title="ویرایش"
                                                   href="{{url('admin/session/edit/'.$row['id'])}}">
                                                    <i class="d-flex bi bi-pencil-square color-custom2 fs-5"></i>
                                                </a>
                                                <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip" data-bs-title="حذف"
                                                   onclick="confirmDelete('/admin/session/delete/{{ @$row['id'] }}')"
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
                        @if(count($session))
                            @include('admin.components.pagination.default',['paginator'=>$session])
                        @endif
                    </form>
                </div>
                @if (@$corse)
                    <div class="col-lg-3 col-sm-6 col-12 table-responsive mt-lg-0 mt-2">
                        <h4>ترتیب نمایش </h4>

                        <ul class="list-unstyled" id="sortable">
                            @foreach($session as $key=>$row)
                                <li class="my-2 border bg-custom ui-sortable-handle p-2 rounded-custom" data-id="{{ $row['id'] }}">{{$row['title']}}</li>
                            @endforeach
                        </ul>

                    </div>
                @endif

            </div>
        </div>
    </div>


    {{--  search modal  --}}
    <div id="myModal" class="modal fade" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
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
                            <div class="col-sm-6 p-2">
                                <div class="form-group">
                                    <label>
                                        عنوان
                                    </label>
                                    <input name="title" id="" class="form-control rounded-custom">
                                </div>
                            </div>
                            <div class="col-sm-6 p-2">
                                <div class="form-group">
                                    <label>
                                        دسته
                                    </label>
                                    <select name="parent_id" id="" class="form-select rounded-custom">
                                        @foreach ($session as $cat)
                                            <option value="{{$cat['id']}}">
                                                {{$cat['title']}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit"  id="submitFormCms" class="btn btn-custom rounded-custom w-fit px-4 ms-auto">
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
<link rel="stylesheet" href="{{ asset('assets/admin/css/jquery-ui.css') }}">
<script src="{{ asset('assets/admin/js/jquery-ui.min.js') }}"></script>
<script>
{{--  $( function() {
  $("#sortable-table tbody").sortable({
    update: function(event, ui) {
            var newOrder = $(this).sortable('toArray', { attribute: 'data-id' });
            saveNewOrder(newOrder);
            }
        });
    });  --}}
    $( function() {
      $( "#sortable" ).sortable({
            update: function(event, ui) {
                    var newOrder = $(this).sortable('toArray', { attribute: 'data-id' });
                    saveNewOrder(newOrder);
                  }
        });
    });
function saveNewOrder(newOrder) {
    $.ajax({
      url: '/admin/session/update-order',
      method: 'POST',
      data: {
        "_token": "{{ csrf_token() }}",
         order: newOrder
         },
      success: function(response) {
      },
      error: function(xhr, status, error) {
        console.error('خطا در ارسال ترتیب به سرور');
      }
    });
  }
</script>
    @include('admin._layouts.blocks.utils.confirmDelete')
@endpush
