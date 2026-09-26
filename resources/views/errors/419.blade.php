@extends(request()->is('admin') || request()->is('admin/*') ? 'errors.admin-layout' : 'errors.site-layout')

@section('title', 'نشست منقضی شده')
@section('code', '419')
@section('message', 'نشست شما منقضی شده است. لطفاً صفحه را رفرش کنید و دوباره تلاش کنید.')
@section('hint', 'این خطا معمولاً بعد از مدتی بی‌فعالیتی یا بستن مرورگر رخ می‌دهد.')
