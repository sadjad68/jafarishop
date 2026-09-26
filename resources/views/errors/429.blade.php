@extends(request()->is('admin') || request()->is('admin/*') ? 'errors.admin-layout' : 'errors.site-layout')

@section('title', 'درخواست‌های زیاد')
@section('code', '429')
@section('message', 'تعداد درخواست‌های شما بیش از حد مجاز است.')
@section('hint', 'لطفاً چند لحظه صبر کنید و دوباره تلاش کنید.')
