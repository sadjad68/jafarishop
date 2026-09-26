<!doctype html>
<html lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
        لیبل پستی
    </title>
    <style>
        @page {
            margin: 0;
            size: auto;
        }

        @font-face {
            font-family: "yekan-medium-FA";
            font-style: normal;
            src: url('{{ asset('assets/site/fonts/iranyekan/woff/iranyekanwebmediumfanum.woff') }}');
            font-display: swap;
        }

        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            direction: rtl;
            background: #fff;
            font-family: yekan-medium-FA !important;
        }

        .card {
            width: 150mm;
            box-sizing: border-box;
            padding: 10px;
            position: relative;
            border: 3px solid #444;
            border-radius: 16px;
            margin: auto;
        }

        .sender-box {
            border: 1px solid #444;
            border-radius: 10px;
            padding: 10px;
            font-size: 13px;
        }

        .sender-title {
            font-size: 11px;
            color: #666;
            margin-bottom: 10px;
        }

        .field {
            margin-bottom: 10px;
            display: flex;
            gap: 5px;
            font-size: 13px;
        }

        .receiver-title {
            margin-top: 10px;
            font-weight: bold;
        }

        .address-box {
            border: 1px dashed #aaa;
            padding: 8px;
            min-height: 50mm;
            font-size: 13px;
            margin-top: 5px;
        }

        .bottom-line {
            border-top: 1px dotted #555;
            width: 60mm;
            height: 14px;
        }

        .side-logo {
            width: 40%;
        }

        .side-logo img {
            width: 100%;
        }

        .sendr {
            width: 60%;
            background-color: #fff;
            border: 1px solid #444;
            padding: 12px;
            border-radius: 10px;
            align-items: start;
            display: flex;
            height: max-content;
        }

        .sendr p {
            margin: 0 !important;
        }

        .boxs {
            display: flex;
            padding-bottom: 10px;
            gap: 10px;
        }

        .boxs-cord {
            margin: auto
        }

        /* بخش اصلاح شده فقط برای مخفی سازی دکمه بدون تغییر ظاهر کارت */
        @media print {

            .no-print,
            #print-button,
            .header {
                display: none !important;
            }

            body {
                height: auto !important;
                background: #fff !important;
            }

            .card {
                margin: 20px auto !important;
                border: 3px solid #444 !important;
                box-shadow: none !important;
            }
        }
    </style>
</head>

<body>
    <div class="boxs-cord">
        <div class="no-print">
            <button class="print-button btn btn-one" id="print-button"
                style="padding: 10px 30px;
                cursor: pointer;
                background-color: #333;
                border: 1px solid #333;
                color: #fff;
                display: flex;
                justify-content: end;
                margin-right: auto;
                margin-bottom: 10px;
                border-radius: 7px;
                font-family: yekan-medium-FA !important;">
                چاپ لیبل
            </button>
        </div>

        <div class="card">
            <div class="boxs">
                <div class="side-logo">
                    <img src="{{ @$settings['logo'] }}" />
                </div>
                <div class="sendr">
                    <ul style="margin: 0; padding: 0;display: flex; flex-direction: column; gap: 4px;">
                        <li style="list-style: none; padding: 4px 0;">
                            <p style="font-size: 14px">
                                فرستنده: {{ @$settings['siteName_fa'] }}
                            </p>
                        </li>
                        <li style="list-style: none; padding: 4px 0;">
                            <p style="font-size: 14px">
                                آدرس: {{ @$settings['address'] }}
                            </p>
                        </li>
                        <li style="list-style: none; padding: 4px 0;">
                            <p style="font-size: 14px">
                                راه ارتباطی:
                                تماس:
                                <span dir="ltr">
                                    @foreach (@$settings['phone_numbers'] as $number)
                                        {{ $number }} <br>
                                    @endforeach
                                </span>
                            </p>
                        </li>
                        @if (@$instagram)
                            <li style="list-style: none; padding: 4px 0;">
                                <p style="margin: 0; display: flex; gap: 5px; align-items: center;">
                                    <img width="18" src="{{ asset('assets/site/images/instagram-label.png') }}" />
                                    <span style="margin-top: 1px">
                                        {{ $instagram }}
                                    </span>
                                </p>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="sender-box">
                <div class="field">
                    <div class="label">
                        گیرنده:
                    </div>
                    <div class="line">
                        {{ $data->receiptor_full_name }}
                    </div>
                </div>
                <div class="field">
                    <div class="label">
                        آدرس:
                    </div>
                    <div class="line" style="line-height: 1.75;">
                        {{ json_decode(@$data->address, true) ? json_decode(@$data->address, true)['state'] . ' ' . json_decode(@$data->address, true)['city'] . ' ' . json_decode(@$data->address, true)['address'] : 'آدرس نادرست' }}
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div class="field" style="width: 50%;">
                        <div class="label">
                            کدپستی:
                        </div>
                        <div class="line">
                            {{ json_decode(@$data->address, true) ? json_decode(@$data->address, true)['postal_code'] : 'آدرس نادرست' }}
                        </div>
                    </div>
                    <div class="field" style="width: 50%;">
                        <div class="label">
                            شماره تماس:
                        </div>
                        <div class="line">
                            {{ json_decode(@$data->address, true) ? json_decode(@$data->address, true)['receiptor_mobile'] : 'آدرس نادرست' }}
                        </div>
                    </div>
                </div>
{{--                <div class="field">--}}
{{--                    <div class="label">--}}
{{--                        ارزش مرسوله:--}}
{{--                    </div>--}}
{{--                    <div class="line">--}}
{{--                        {{ number_format($data->payment_price) . ' تومان ' }}--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
        </div>
    </div>


    <script>
        var printButton = document.getElementById('print-button');
        printButton.addEventListener('click', function() {
            window.print();
        })
    </script>
</body>

</html>
