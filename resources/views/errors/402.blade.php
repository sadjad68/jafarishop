@extends(request()->is('admin') || request()->is('admin/*') ? 'errors.admin-layout' : 'errors.site-layout')

@section('title', 'پرداخت لازم است')
@section('code', '402')
@section('message', 'برای ادامه، پرداخت یا اشتراک فعال لازم است.')
@section('hint', 'در صورت نیاز با پشتیبانی تماس بگیرید.')
