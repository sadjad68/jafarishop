@extends('admin._layouts.master')
@section('title','داشبورد')
@section('content')
{{--    just use the <span> </span> tags below for each one--}}
    <ul class="d-flex my-5 list-unstyled">
        <li class="mx-5">
            <span class="badge  bg-label-info">
               تست
     </span>
        </li>
        <li class="mx-5">
            <span class="badge  bg-label-primary">
               تست
     </span>
        </li>
        <li class="mx-5">
            <span class="badge  bg-label-success">
               تست
     </span>
        </li>
        <li class="mx-5">
            <span class="badge  bg-label-warning">
               تست
     </span>
        </li>
        <li class="mx-5">
            <span class="badge  bg-label-danger">
               تست
     </span>
        </li>
    </ul>

@endsection
