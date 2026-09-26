@extends(request()->is('admin') || request()->is('admin/*') ? 'errors.admin-layout' : 'errors.site-layout')

@section('title', 'دسترسی غیرمجاز')
@section('code', '401')
@section('message', 'برای مشاهده این صفحه باید وارد حساب کاربری خود شوید.')
@section('hint', 'اگر قبلاً وارد شده‌اید، نشست شما ممکن است منقضی شده باشد.')
