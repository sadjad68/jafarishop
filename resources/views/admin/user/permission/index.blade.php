@extends('admin._layouts.master')

@section('title')
    دسترسی
@endsection

@section('content')
    <script>
        function confirmDelete(id) {
            swal('آیا از حذف سطح دسترسی مطمئن هستید؟', '', "warning", {
                buttons: {
                    accept: "تایید و حذف",
                    cancel: "لغو",
                },
            }).then((value) => {
                switch (value) {
                    case "accept":
                        location.href = "{{ url('/admin/permission/delete') }}/" + id;
                        break;
                }
            });
        }
    </script>

        <div class="container-fluid">
            <div class="card-block row">
                <div class="col-lg-8 col-12 mx-auto">
                    <div class="page-header">
                        <div
                            class="card-header py-3 no-bg border-0 px-0 flex-wrap">

                            <div class="d-flex align-items-center justify-content-between w-100">
                                <h3 class="fw-bolder">
                                    سطح دسترسی
                                </h3>
                                <a href="{{ url('admin/permission/add') }}"
                                   class="btn ms-2 my-2 btn-custom-b rounded-custom d-flex align-items-center">
                                    <i class="bi bi-plus-square-dotted d-flex h5 my-0 me-2"></i>
                                    افزودن
                                </a>
                            </div>
                        </div>
                    </div>
                    <form class="form-control">
                        <div class="table-responsive">
                            <table id="myDataTable"
                                   class="table align-middle border-custom mb-0">
                                <thead class="text-center text-light">
                                <tr>
                                    <th class="fw-bolder" style="width: 20px;">
                                        #
                                    </th>
                                    <th class="fw-bolder" style="width: 10%;">
                                        نام
                                    </th>
                                    <th class="fw-bolder" style="width:100px">
                                        عملیات
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="text-center text-light">
                                @foreach ($data as $key => $row)
                                    <tr>
                                        <th>
                                            {{$key + 1}}
                                        </th>
                                        <th>
                                            {{ @$row->name }}
                                        </th>
                                        <th>
                                            <div class="btn-group" role="group">
                                                <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip" data-bs-title="ویرایش"
                                                   href="{{ url('admin/permission/edit/' . $row->id) }}">
                                                    <i class="d-flex bi bi-pencil-square color-custom2 fs-5"></i>
                                                </a>
                                                <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip" data-bs-title="حذف" onclick="return confirm('مطمعن هستید؟؟')"
                                                   href="{{ url('admin/permission/delete/' . $row->id) }}">
                                                    <i class="d-flex bi bi-trash3 color-custom2 fs-5"></i>
                                                </a>
                                            </div>
                                        </th>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>

@endsection

@push('styles')
    <style>
        ul {
            padding: 0px;
            margin: 0px;
        }

        #response {
            padding: 10px;
            background-color: #9F9;
            border: 2px solid #396;
            margin-bottom: 20px;
        }

        #list li {
            margin: 0 0 3px;
            padding: 8px;
            background-color: #333;
            color: #fff;
            list-style: none;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{ asset('assets/admin/libs/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/js/bootstrap-datepicker.fa.min.js') }}"></script>

    <script>
        $(".date").datepicker({
            changeMonth: true,
            changeYear: true,
            isRTL: true
        });
        $(document).ready(function() {
            $('#check-all').change(function() {
                $(".delete-all").prop('checked', $(this).prop('checked'));
            });
        });
    </script>

    <meta name="csrf-token" content="{!! csrf_token() !!}" />
    {{--    <script type="text/javascript"> --}}
    {{--        $(document).ready(function () { --}}
    {{--            function slideout() { --}}
    {{--                setTimeout(function () { --}}
    {{--                    $("#response").slideUp("slow", function () { --}}
    {{--                    }); --}}

    {{--                }, 2000); --}}
    {{--            } --}}

    {{--            $("#response").hide(); --}}
    {{--            $(function () { --}}
    {{--                $("#list ul").sortable({ --}}
    {{--                    opacity: 0.8, cursor: 'move', update: function () { --}}
    {{--                        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content'); --}}
    {{--                        var order = $(this).sortable("serialize") + '&update=update' + '&_token=' + CSRF_TOKEN; --}}
    {{--                        $.post("{!!URL::action('Admin\ArticleController@postSort')!!} ", order, function (theResponse) { --}}
    {{--                            $("#response").html(theResponse); --}}
    {{--                            $("#response").slideDown('slow'); --}}
    {{--                            slideout(); --}}
    {{--                        }); --}}

    {{--                    } --}}
    {{--                }); --}}
    {{--            }); --}}

    {{--        }); --}}
    {{--    </script> --}}


@endpush
