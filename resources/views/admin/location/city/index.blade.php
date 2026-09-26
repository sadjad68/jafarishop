@extends('admin._layouts.master')

@section('title')
  شهر  ها
@stop
@section('content')
    <div class="body d-flex py-3" id="cms-form">
        <div class="container-fluid">
            <div class="page-header">
                <div
                    class="card-header py-3 no-bg bg-transparent border-0 px-0 flex-wrap">
                    <h3 class="fw-bolder mb-0">
                      شهر  ها
                    </h3>
                    <div class="d-flex flex-md-row flex-column align-items-md-center justify-content-md-between w-100">


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
                                        نام
                                    </th>
                                    <th class="fw-bolder">
                                        استان
                                    </th>
                                    <th class="fw-bolder" >
                                 وضعیت
                                    </th>

                                    <th class="fw-bolder" >
                                        عملیات
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="text-center text-light">
                                @foreach($cities as $key=>$row)
                                    <tr>
                                        {{--                                    <th>--}}
                                        {{--                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">--}}
                                        {{--                                    </th>--}}
                                        <th>
                                            {{$key + 1}}
                                        </th>
                                        <th>
                                        <span style="font-size: 14px">
                                            {{$row['name']}}
                                        </span>

                                        </th>
                                        <th>

                                            <span style="font-size: 14px">
                                            {{$row->state->name}}
                                        </span>
                                        </th>
                                        <th>
                                        <span class="badge bg-label-{{$row->status_name['badge']}}"
                                              style="font-size: 11px">
                                            {{@$row->status_name['title']}}
                                        </span>
                                        </th>

                                        <th>

                                            <div class="btn-group" role="group">
                                                <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip" data-bs-title="تغییر وضعیت"
                                                   href="{{route('admin.city.change-status',['id'=>$row->id])}}">
                                                    <i class="d-flex bi bi-arrow-repeat color-custom2 fs-5"></i>
                                                </a>

                                            </div>
                                        </th>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        @component("admin.components.pagination.default")
                            @slot("paginator",$cities)
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
                                    <input name="name" id="" class="form-control rounded-custom">
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
@endpush
