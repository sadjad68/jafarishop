@extends(request()->is('admin') || request()->is('admin/*') ? 'errors.admin-layout' : 'errors.site-layout')

@section('title', 'خطای سرور')
@section('code', $exception->getStatusCode())
@section('message', 'خطای داخلی سرور رخ داده است.')
@section('hint', 'لطفاً بعداً دوباره تلاش کنید.')
