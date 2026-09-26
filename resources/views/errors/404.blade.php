@extends(request()->is('admin') || request()->is('admin/*') ? 'errors.admin-layout' : 'errors.site-layout')

@section('title', 'صفحه پیدا نشد')
@section('code', '404')
@section('message', 'صفحه‌ای که دنبال آن هستید وجود ندارد یا منتقل شده است.')
@section('hint', 'آدرس را بررسی کنید یا از صفحه اصلی مسیر درست را پیدا کنید.')
