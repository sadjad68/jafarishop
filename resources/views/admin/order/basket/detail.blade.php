@extends('admin._layouts.master')

@section('title')
    جزئیات سبد خرید
@stop

@section('content')
<div class="row w-100 m-0 mt-5">
    {{-- اطلاعات کاربر --}}
    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
        <div class="card">
            <h5 class="card-header text-primary">
                <i class="fa fa-user"></i> اطلاعات کاربر
            </h5>
            <div class="card-body px-1">
                <table class="table table-striped table-bordered">
                    <tbody>
                        <tr>
                            <th>نام کاربر</th>
                            <td>{{ @$basket->user->full_name ?? 'ناشناس'}} ( {{ @$basket->user->mobile }} )</td>
                        </tr>
                        <tr>
                            <th>کد کاربر</th>
                            <td>{{ @$basket->user->id }}</td>
                        </tr>
                        <tr>
                            <th>آدرس</th>
                            <td>
                                @if(@$basket->address)
                                    {{ $basket->address->state->name }} - {{ $basket->address->city->name }} - {{ $basket->address->address }}
                                    <br>کد پستی: {{ $basket->address->postal_code }}
                                    <br>تلفن: {{ $basket->address->receiptor_mobile }}
                                @else
                                    بدون آدرس
                                @endif
                            </td>
                        </tr>
                        @if(@$basket->user)
                            <tr>
                                <th>آیا سفارش موفق دارد؟</th>
                                <td>
                                    @if($basket->user->orders->where('order_status','paid')->first())
                                        <span class="badge bg-success">بله</span>
                                        <a href="{{route('admin.order.index',['filter'=>true,'user_id'=>$basket->user_id])}}"
                                           target="_blank"
                                           class="btn my-2 btn-custom rounded-custom d-flex align-items-center text-center">
                                            مشاهده فاکتور ها
                                        </a>
                                    @else
                                        <span class="badge bg-danger">خیر</span>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
        <div class="card">
            <h5 class="card-header text-primary">
                <i class="fa fa-file"></i> اطلاعات سبد خرید
            </h5>
            <div class="card-body px-1">
                <table class="table table-striped table-bordered">
                    <tbody>
                        <tr>
                            <th>شماره سبد خرید</th>
                            <td>{{ $basket->id }}</td>
                        </tr>
                        <tr>
                            <th>مبلغ نهایی</th>
                            <td>{{ number_format($basket->final_price + $data['shipping_price']) }} تومان</td>
                        </tr>
                        <tr>
                            <th>هزینه ارسال</th>
                            <td>{{ $data['shipping_price'] > 0 ? number_format($data['shipping_price'])."تومان" : "رایگان" }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- لیست محصولات --}}
    <div class="col-12 mt-4">
        <div class="card">
            <h5 class="card-header text-primary">
                <i class="fa fa-shopping-cart"></i> محصولات سبد خرید
            </h5>
            <div class="card-body px-1">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>عنوان محصول</th>
                                <th>تصویر</th>
                                <th>قیمت</th>
                                <th>تعداد</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($basket->items as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        @if (@$item->product)
                                        <a target="_blank" href="{{ route("product.detail",@$item->product->url) }}">{{ @$item->product->title }}</a>
                                        @endif
                                    </td>
                                    <td>
                                        <img src="{{ $item->product->getImage() }}" width="50" height="50" class="border rounded">
                                    </td>
                                    <td>{{ number_format($item->product_variant_id ? $item->productVariant->final_price : $item->product->final_price) }} تومان</td>
                                    <td>{{ $item->quantity }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
