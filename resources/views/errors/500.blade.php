@extends(request()->is('admin') || request()->is('admin/*') ? 'errors.admin-layout' : 'errors.site-layout')

@section('title', 'خطای سرور')
@section('code', '500')
@section('message', 'مشکلی در سرور رخ داده است. تیم فنی در حال بررسی است.')
@section('hint', 'لطفاً چند دقیقه دیگر دوباره تلاش کنید.')
