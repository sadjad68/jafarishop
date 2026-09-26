@extends(request()->is('admin') || request()->is('admin/*') ? 'errors.admin-layout' : 'errors.site-layout')

@section('title', 'دسترسی ممنوع')
@section('code', '403')
@section('message', 'شما اجازه دسترسی به این صفحه را ندارید.')
@section('hint', 'اگر فکر می‌کنید این یک خطاست، با مدیر سایت تماس بگیرید.')
