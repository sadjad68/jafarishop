@extends(request()->is('admin') || request()->is('admin/*') ? 'errors.admin-layout' : 'errors.site-layout')

@section('title', 'درخواست نامعتبر')
@section('code', $exception->getStatusCode())
@section('message', 'درخواست شما قابل پردازش نیست.')
@section('hint', 'لطفاً آدرس را بررسی کنید یا به صفحه اصلی برگردید.')
