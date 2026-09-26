<link rel="stylesheet" href="{{ asset('assets/site/css/product/tpl-product-detail.css?v3.18') }}" />
<link rel="stylesheet" href="{{ asset('assets/site/css/product/tpl-magiczoomplus.css') }}" />
<style>
    @media(max-width:576px) {
        body:not(.pdp-sticky-active) .btn-to-top {
            bottom: calc(1rem + env(safe-area-inset-bottom, 0px));
        }
    }
    .variant-selection-container {
        /* بسیار مهم: برای اینکه overlay به درستی روی این قسمت قرار گیرد */
        position: relative;
    }

    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(255, 255, 255, 0.7);
        /* پس‌زمینه سفید کمی شفاف */
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 10;
        /* مطمئن شوید که روی بقیه محتوا قرار گیرد */
        cursor: wait;
        /* تغییر نشانگر ماوس برای بازخورد بهتر */
    }

    .spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        /* رنگ اصلی لودینگ */
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>
