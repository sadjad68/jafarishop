@extends('admin._layouts.master')

@section('title')
    نمایندگی های چاپار
@stop
@section('content')
<div class="body d-flex py-3">
    <div class="container-fluid">
        <div class="page-header">
            <div class="card-header py-3 no-bg bg-transparent border-0 px-0 flex-wrap">
                <h3 class="fw-bolder mb-0">
                    نمایندگی های چاپار
                </h3>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="card-block row">
            <div class="col-sm-12 col-lg-12 col-xl-12">

                <div class="container">
                    <div class="row">
                        @foreach($cityNames as $index => $cityName)
                            <div class="col-md-3 mb-3">
                                <div class="border p-2 text-center">
                                    {{ $cityName }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>



            </div>
        </div>
    </div>

</div>

@endsection

