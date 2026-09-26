@extends('admin._layouts.master')
@section('title','داشبورد')
@section('content')

<div class="border p-1">
    <label for="variables_id" class="col-form-label" style="font-weight: bold;">
        انتخاب متغییر شماره یک :
    </label>
    <div class="bg-light p-3">
        <div class="position-relative mb-2 border overflow-hidden rounded-5">
            <input type="text" class="form-control form-control-sm rounded-5 border-0" placeholder="نوشتن متغییر">
            <button type="submit"
                class="btn btn-success btn-sm p-2 rounded-0 border-0 shadow-none position-absolute top-0 bottom-0 end-0">
                <i class="bi bi-check2 d-flex fs-5"></i>
            </button>
        </div>
        <input type="text" class="form-control form-control-sm rounded-5 mb-2" id="searchInput1"
            placeholder="جستجو در متغییر شماره یک">
        <div class="sd-checkbox ">
            <ul id="items1" class="p-0 m-0" style="list-style-type:none">
                <li>
                    <label class="custom-ch">
                        متغییر شماره یک برای متغییر شماره یک
                        <input type="checkbox" value="1" name="variables" class="form-control" multiple>
                        <span class="checkmark"></span>
                    </label>
                </li>
                <li>
                    <label class="custom-ch">
                        متغییر شماره یک برای متغییر شماره یک
                        <input type="checkbox" value="1" name="variables" class="form-control" multiple>
                        <span class="checkmark"></span>
                    </label>
                </li>
                <li>
                    <label class="custom-ch">
                        متغییر شماره یک برای متغییر شماره یک
                        <input type="checkbox" value="1" name="variables" class="form-control" multiple>
                        <span class="checkmark"></span>
                    </label>
                </li>
                <li>
                    <label class="custom-ch">
                        متغییر شماره یک برای متغییر شماره یک
                        <input type="checkbox" value="1" name="variables" class="form-control" multiple>
                        <span class="checkmark"></span>
                    </label>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
