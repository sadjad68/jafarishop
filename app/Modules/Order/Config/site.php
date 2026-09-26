<?php

return [
    'name' => 'Orders',
    'my_banks' => [
        'zarinPal' => [
            'MerchantId' => [
                'value' => 'کد مرچنت',
                'type' => 'string'
            ],
        ],
        'zibal' => [
            'MerchantId' => [
                'value' => 'کد مرچنت',
                'type' => 'string'
            ],
        ],
        'aqayepardakht' => [
            'pin' => [
                'value' => 'پین درگاه',
                'type' => 'string'
            ],
        ],
        'saderat' => [
            'TerminalID' => [
                'value' => 'ترمینال آیدی',
                'type' => 'string'
            ],
        ],
        'snappay' => [
            'client_id' => [
                'value' => 'client id',
                'type' => 'string'
            ],
            'client_secret' => [
                'value' => 'client secret',
                'type' => 'string'
            ],
            'username' => [
                'value' => 'username',
                'type' => 'string'
            ],
            'password' => [
                'value' => 'password',
                'type' => 'string'
            ],
            'base_url' => [
                'value' => 'base url',
                'type' => 'string'
            ],
        ],
        'sadad' => [
            'MerchantId' => [
                'value' => 'کد مرچنت',
                'type' => 'string'
            ],
            'key' => [
                'value' => 'کد ترمینال',
                'type' => 'string'
            ],
            'TerminalId' => [
                'value' => 'آی دی ترمینال',
                'type' => 'string'
            ],
        ],
        'parsian' => [
            'LoginAccount' => [
                'value' => 'Login Account',
                'type' => 'string'
            ],
            'TerminalId' => [
                'value' => 'آیدی ترمینال',
                'type' => 'string'
            ]
        ],
        'sep' => [
            'TerminalId' => [
                'value' => 'آی دی ترمینال',
                'type' => 'string'
            ],
        ],
        'irandargah' => [
            'MerchantId' => [
                'value' => 'کد مرچنت',
                'type' => 'string'
            ],
        ],
        'digipay' => [
            'client_id' => [
                'value' => 'Client Id',
                'type' => 'string',
            ],
            'client_secret' => [
                'value' => 'Client Secret',
                'type' => 'string',
            ],
            'username' => [
                'value' => 'نام کاربری (دیجی‌پی)',
                'type' => 'string',
            ],
            'password' => [
                'value' => 'رمز عبور',
                'type' => 'string',
            ],
            'is_test' => [
                'value' => 'درگاه تستی (UAT)',
                'type' => 'checkbox',
            ],
            'preferred_gateway' => [
                'value' => 'درگاه ترجیحی (خالی = UPG، 0=کیف‌پول، 2=IPG)',
                'type' => 'options',
                'values' => [
                    'UPG (انتخاب ابزار پرداخت)' => '',
                    'کیف پول (Wallet)' => 0,
                    'درگاه اینترنتی (IPG)' => 2,
                ],
            ],
        ],
        'cardtocard' => [
            'card_number' => [
                'value' => 'شماره کارت',
                'type' => 'string',
            ],
            'shaba_number' => [
                'value' => 'شماره شبا',
                'type' => 'string',
            ],
            'account_holder_name' => [
                'value' => 'نام صاحب حساب',
                'type' => 'string',
            ],
            'reservation_expire_minutes' => [
                'value' => 'مهلت تایید فیش و نگهداری رزرو موجودی (دقیقه)',
                'type' => 'number',
            ],
        ],
    ],
    'shipping_methods' => [
        'chapar' => [
            'chapar_type' => [
                'value' => 'سرویس چاپار',
                'values' => [
                    'زمینی' => 1,
                    'هوایی' => 6,
                    'پست' => 11,
                    'چاپار پلاس' => 35,
                    'پاکت' => 97,

                ],
                'type' => 'options'
            ],
            'user_name' => [
                'value' => 'نام کاربری چاپار',
                'type' => 'string'
            ],
            'password' => [
                'value' => 'رمز عبور چاپار',
                'type' => 'string'
            ],

        ],
    ],
    'bank_settings' => [
        "62.60.206.26" => ["title" => "Ip(ای پی)"],
        "443" => ["title" => "پورت آدرس برگشت"],
        "3306" => ["title" => "پورت پایگاه داده"],
        "php-laravel (پی اچ پی - لاراول)" => ["title" => "پلتفرم نرم افزار"],
        url('/checkout/finish') => ["title" => "آدرس برگشت برای درگاه زرین پال"],
        url('/checkout/finish-saman') => ["title" => "آدرس برگشت برای درگاه سپ"],
        url('/checkout/finish-sadad') => ["title" => "آدرس برگشت برای درگاه سداد"],
        url('/checkout/finish-snapp-pay') => ["title" => "آدرس برگشت برای درگاه اسنپ پی"],
        url('/checkout/finish-ir-dargah') => ["title" => "آدرس برگشت برای درگاه ایران درگاه"],
        url('/checkout/finish-parsian') => ["title" => "آدرس برگشت برای درگاه پارسیان (سینا)"],
        url('/checkout/finish-zibal') => ["title" => "آدرس برگشت برای درگاه زیبال"],
        url('/checkout/finish-aqayepardakht') => ["title" => "آدرس برگشت برای درگاه آقای پرداخت"],
        url('/checkout/finish-digipay') => ["title" => "آدرس برگشت برای درگاه دیجی‌پی (UPG)"],
    ],
];
