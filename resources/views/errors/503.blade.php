@extends(request()->is('admin') || request()->is('admin/*') ? 'errors.admin-layout' : 'errors.site-layout')

@section('title', 'در حال تعمیر')
@section('code', '503')
@section('message', 'سایت به‌صورت موقت در دسترس نیست. به‌زودی برمی‌گردیم.')
@section('hint', 'از صبر و شکیبایی شما سپاسگزاریم.')
