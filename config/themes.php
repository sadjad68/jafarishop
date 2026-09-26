<?php


return [
    "theme1" => [
        'name' => 'لومیرا (خدماتی + فروشگاهی)',
        'value' => 'theme1',
        'css' => [
           'main'=> 'assets/site/css/shared/tpl-site-public.css?v1.08'
        ],
        'js'=>[],
        'adminSections'=>[
        ],
        'siteSections'=>[
            'logo',
            'search'
        ],
        'menuCount'=>7,
        'firstPageSections'=>[
            'banner',
            'service',
            'sample',
            'product_category',
            'product',
            'gallery',
            'gallery_category',
            'certification',
            'package',
            'manager',
            'tag',
        ],
        'sliderSizes'=>[
            'desktop' => [
                'width' => 2000,
                'height' => 1000,
            ],
            'mobile' => [
                'width' => 500,
                'height' => 750,
            ]
        ],

    ],
    "theme2" => [
        'name' => 'مارکتو (فروشگاهی)',
        'value' => 'theme2',
        'css' => [

           'main'=> 'assets/site/css/shop/tpl-theme2-public.css?v0.68'
        ],
        'js'=>[],
        'adminSections'=>[
            'highlight'
        ],
        'siteSections'=>[
        ],
        'menuCount'=>12,

        'firstPageSections'=>[
            'banner',
            'service',
            'highlight',
            'product_category',
            'product',
            'tag',
            'brand',
            'blog',
        ],
        'sliderSizes'=>[
            'desktop' => [
                'width' => 1900,
                'height' => 415,
            ],
            'mobile' => [
                'width' => 700,
                'height' => 450,
            ]
        ],

    ],
];
