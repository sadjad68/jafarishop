<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاکتور #{{ @$order->id }} — {{ @$settings['siteName_fa'] }}</title>
    <link rel="stylesheet" href="{{ asset('assets/site/css/shared/tpl-bootstrap.rtl.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/site/css/shared/tpl-site-public.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/site/css/panel/tpl-user-panel.css?v=2.3') }}">
</head>

<body>
    @include('components.order.factor-doc', [
        'order' => $order,
        'settings' => $settings,
        'showPaymentStatus' => true,
    ])

    <script>
        document.getElementById('print-button').addEventListener('click', function () {
            window.print();
        });
    </script>
</body>

</html>
