@extends('admin._layouts.master')
@section('title','داشبورد')
@section('content')
    <div class="body d-flex py-3">
        <div class="px-md-5 px-1 py-md-3 py-2">
            <div class="container">

                <div class="admin-hero">
                    <div class="admin-hero-text">
                        <span class="admin-hero-eyebrow">
                            <i class="bi bi-calendar3"></i>
                            {{ jdate('l، d F Y') }}
                        </span>
                        <h2>
                            سلام {{ Auth::user()->full_name ?? 'مدیر' }}
                        </h2>
                        <p>
                            خوش اومدی! از این‌جا می‌تونی سریع به بخش‌های پرکاربرد پنل مدیریت دسترسی داشته باشی، فیچرهای فعلی سایت و آپدیت‌های بعدی رو ببینی.
                        </p>
                    </div>
                    <div class="admin-hero-deco" aria-hidden="true">
                        <i class="bi bi-speedometer2"></i>
                    </div>
                </div>

                <div class="row w-100 m-0 px-0 dash-content">
                    <div class="admin-section-title">
                        <span class="admin-section-ico"><i class="bi bi-lightning-charge"></i></span>
                        <span class="admin-section-label">دسترسی سریع</span>
                        <hr>
                    </div>
                    <div class="dash-box col-xxl-3 col-lg-4 col-sm-6 col-6 p-md-2 p-1">
                        <a href="{{ route('admin.order.index') }}"
                           class="dashbtn d-flex align-items-center justify-content-between p-3">
                            سفارش‌ها
                            <span class="d-flex align-items-center gap-2">
                                @if(($order_count ?? 0) > 0)
                                    <span class="dashbtn-count">{{ $order_count }}</span>
                                @endif
                                <i class="p-2 rounded bi bi-bag-check d-flex"></i>
                            </span>
                        </a>
                    </div>
                    <div class="dash-box col-xxl-3 col-lg-4 col-sm-6 col-6 p-md-2 p-1">
                        <a href="{{ route('admin.basket.index') }}"
                           class="dashbtn d-flex align-items-center justify-content-between p-3">
                            سبدهای خرید
                            <i class="p-2 rounded bi bi-cart3 d-flex"></i>
                        </a>
                    </div>
                    <div class="dash-box col-xxl-3 col-lg-4 col-sm-6 col-6 p-md-2 p-1">
                        <a href="{{ route('admin.discount.index') }}"
                           class="dashbtn d-flex align-items-center justify-content-between p-3">
                            تخفیف‌ها
                            <i class="p-2 rounded bi bi-percent d-flex"></i>
                        </a>
                    </div>
                    <div class="dash-box col-xxl-3 col-lg-4 col-sm-6 col-6 p-md-2 p-1">
                        <a href="{{ route('admin.product-category.index') }}"
                           class="dashbtn d-flex align-items-center justify-content-between p-3">
                            دسته بندی محصولات
                            <i class="p-2 rounded bi bi-diagram-3 d-flex"></i>
                        </a>
                    </div>
                    <div class="dash-box col-xxl-3 col-lg-4 col-sm-6 col-6 p-md-2 p-1">
                        <a href="{{ route('admin.product.index') }}"
                           class="dashbtn d-flex align-items-center justify-content-between p-3">
                            محصولات
                            <i class="p-2 rounded bi bi-box d-flex"></i>
                        </a>
                    </div>
                    <div class="dash-box col-xxl-3 col-lg-4 col-sm-6 col-6 p-md-2 p-1">
                        <a href="{{ route('admin.blog.index') }}"
                           class="dashbtn d-flex align-items-center justify-content-between p-3">
                            مطالب
                            <i class="p-2 rounded bi bi-journal-text d-flex"></i>
                        </a>
                    </div>
                    <div class="dash-box col-xxl-3 col-lg-4 col-sm-6 col-6 p-md-2 p-1">
                        <a href="{{ route('admin.video.index') }}"
                           class="dashbtn d-flex align-items-center justify-content-between p-3">
                            ویدیوها
                            <i class="p-2 rounded bi bi-camera-video d-flex"></i>
                        </a>
                    </div>
                    <div class="dash-box col-xxl-3 col-lg-4 col-sm-6 col-6 p-md-2 p-1">
                        <a href="{{ route('admin.comment.index') }}"
                           class="dashbtn d-flex align-items-center justify-content-between p-3">
                            نظرات
                            <span class="d-flex align-items-center gap-2">
                                @if(($comment_count ?? 0) > 0)
                                    <span class="dashbtn-count">{{ $comment_count }}</span>
                                @endif
                                <i class="p-2 rounded bi bi-chat-dots d-flex"></i>
                            </span>
                        </a>
                    </div>
                    <div class="dash-box col-xxl-3 col-lg-4 col-sm-6 col-6 p-md-2 p-1">
                        <a href="{{ route('admin.contact.index') }}"
                           class="dashbtn d-flex align-items-center justify-content-between p-3">
                            تماس با ما
                            <span class="d-flex align-items-center gap-2">
                                @if(($contact_count ?? 0) > 0)
                                    <span class="dashbtn-count">{{ $contact_count }}</span>
                                @endif
                                <i class="p-2 rounded bi bi-envelope d-flex"></i>
                            </span>
                        </a>
                    </div>
                    <div class="dash-box col-xxl-3 col-lg-4 col-sm-6 col-6 p-md-2 p-1">
                        <a href="{{ route('admin.banner.index') }}"
                           class="dashbtn d-flex align-items-center justify-content-between p-3">
                            بنرها
                            <i class="p-2 rounded bi bi-image d-flex"></i>
                        </a>
                    </div>
                    <div class="dash-box col-xxl-3 col-lg-4 col-sm-6 col-6 p-md-2 p-1">
                        <a href="{{ route('admin.user.index') }}"
                           class="dashbtn d-flex align-items-center justify-content-between p-3">
                            کاربران
                            <i class="p-2 rounded bi bi-people d-flex"></i>
                        </a>
                    </div>
                    <div class="dash-box col-xxl-3 col-lg-4 col-sm-6 col-6 p-md-2 p-1">
                        <a href="{{ route('admin.setting.index') }}"
                           class="dashbtn d-flex align-items-center justify-content-between p-3">
                            تنظیمات
                            <i class="p-2 rounded bi bi-gear d-flex"></i>
                        </a>
                    </div>
                    <div class="dash-box col-xxl-3 col-lg-4 col-sm-6 col-6 p-md-2 p-1">
                        <a href="{{ url('/') }}" target="_blank"
                           class="dashbtn d-flex align-items-center justify-content-between p-3">
                            مشاهده سایت
                            <i class="p-2 rounded bi bi-box-arrow-up-left d-flex"></i>
                        </a>
                    </div>
                </div>

                @if($hasOrders ?? false)
                <div class="row w-100 m-0 mt-4 px-0">
                    <div class="admin-section-title">
                        <span class="admin-section-ico"><i class="bi bi-graph-up-arrow"></i></span>
                        <span class="admin-section-label">نمودار فروش <span id="salesChartRangeLabel">{{ $salesChart['range_label'] ?? '۱ ماه اخیر' }}</span></span>
                        <hr>
                    </div>
                    <div class="col-12 p-md-2 p-1">
                        <div class="admin-chart-card">
                            <div class="admin-chart-toolbar">
                                <div class="admin-chart-ranges" id="salesChartRanges">
                                    <button type="button" class="admin-chart-range @if(($salesRange ?? '30') === '7') is-active @endif" data-range="7">یک هفته اخیر</button>
                                    <button type="button" class="admin-chart-range @if(($salesRange ?? '30') === '30') is-active @endif" data-range="30">۱ ماه اخیر</button>
                                    <button type="button" class="admin-chart-range @if(($salesRange ?? '30') === '60') is-active @endif" data-range="60">۲ ماه اخیر</button>
                                    <button type="button" class="admin-chart-range @if(($salesRange ?? '30') === '365') is-active @endif" data-range="365">۱ سال اخیر</button>
                                    <button type="button" class="admin-chart-range @if(($salesRange ?? '30') === 'all') is-active @endif" data-range="all">کل روزها</button>
                                </div>
                                <div class="admin-chart-types" id="salesChartTypes">
                                    <button type="button" class="admin-chart-range is-active" data-type="bar">میله‌ای</button>
                                    <button type="button" class="admin-chart-range" data-type="line">خطی</button>
                                </div>
                            </div>
                            <div class="admin-chart-stats">
                                <div class="admin-chart-stat is-paid">
                                    <span>فاکتور پرداخت‌شده</span>
                                    <strong><span id="salesPaidTotal">{{ number_format($salesChart['paid_total'] ?? 0) }}</span> <small>تومان</small></strong>
                                    <em><span id="salesPaidCount">{{ number_format($salesChart['paid_count'] ?? 0) }}</span> فاکتور</em>
                                </div>
                                <div class="admin-chart-stat is-unpaid">
                                    <span>فاکتور پرداخت‌نشده</span>
                                    <strong><span id="salesUnpaidTotal">{{ number_format($salesChart['unpaid_total'] ?? 0) }}</span> <small>تومان</small></strong>
                                    <em><span id="salesUnpaidCount">{{ number_format($salesChart['unpaid_count'] ?? 0) }}</span> فاکتور</em>
                                </div>
                            </div>
                            <div class="admin-chart-canvas-wrap">
                                <canvas id="salesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

{{--                @include('admin.dashboard._upcoming')--}}

                @include('admin.dashboard._features')

            </div>
        </div>
    </div>
@endsection
@if($hasOrders ?? false)
@push('scripts')
    <script src="{{ asset('assets/admin/js/chart.js') }}"></script>
    <script>
        (function () {
            var canvas = document.getElementById('salesChart');
            if (!canvas || typeof Chart === 'undefined') {
                return;
            }

            var styles = getComputedStyle(document.documentElement);
            var textColor = (styles.getPropertyValue('--admin-text') || '#e8eef8').trim();
            var mutedColor = (styles.getPropertyValue('--admin-muted') || 'rgba(232,238,248,0.68)').trim();
            var gridColor = (styles.getPropertyValue('--admin-stroke') || 'rgba(255,255,255,0.12)').trim();
            var chartUrl = @json(route('admin.dashboard'));
            var chartData = {
                labels: @json($salesChart['labels'] ?? []),
                paid: @json($salesChart['paid_amounts'] ?? []),
                unpaid: @json($salesChart['unpaid_amounts'] ?? [])
            };
            var storedType = localStorage.getItem('admin-sales-chart-type');
            var chartType = storedType === 'line' ? 'line' : 'bar';

            function formatNumber(value) {
                return Number(value || 0).toLocaleString('en-US');
            }

            function tickLimit(labels) {
                var count = (labels || []).length;
                if (count <= 8) return count;
                if (count <= 14) return 8;
                if (count <= 60) return 10;
                return 12;
            }

            function formatAxisValue(value) {
                if (value >= 1000000000) {
                    return (value / 1000000000) + 'B';
                }
                if (value >= 1000000) {
                    return (value / 1000000) + 'M';
                }
                if (value >= 1000) {
                    return (value / 1000) + 'K';
                }
                return value;
            }

            function datasetsFor(type) {
                if (type === 'line') {
                    return [
                        {
                            label: 'پرداخت شده',
                            data: chartData.paid,
                            borderColor: '#22c55e',
                            backgroundColor: 'rgba(34, 197, 94, 0.14)',
                            pointBackgroundColor: '#22c55e',
                            pointBorderColor: '#22c55e',
                            pointHoverBackgroundColor: '#fff',
                            pointHoverBorderColor: '#22c55e',
                            borderWidth: 2,
                            pointRadius: 3,
                            pointHoverRadius: 5,
                            lineTension: 0.35,
                            fill: true
                        },
                        {
                            label: 'پرداخت نشده',
                            data: chartData.unpaid,
                            borderColor: '#fb7185',
                            backgroundColor: 'rgba(251, 113, 133, 0.12)',
                            pointBackgroundColor: '#fb7185',
                            pointBorderColor: '#fb7185',
                            pointHoverBackgroundColor: '#fff',
                            pointHoverBorderColor: '#fb7185',
                            borderWidth: 2,
                            pointRadius: 3,
                            pointHoverRadius: 5,
                            lineTension: 0.35,
                            fill: true
                        }
                    ];
                }

                return [
                    {
                        label: 'پرداخت شده',
                        data: chartData.paid,
                        backgroundColor: 'rgba(34, 197, 94, 0.82)',
                        hoverBackgroundColor: '#22c55e',
                        borderColor: 'transparent',
                        barPercentage: 0.88,
                        categoryPercentage: 0.62
                    },
                    {
                        label: 'پرداخت نشده',
                        data: chartData.unpaid,
                        backgroundColor: 'rgba(251, 113, 133, 0.82)',
                        hoverBackgroundColor: '#fb7185',
                        borderColor: 'transparent',
                        barPercentage: 0.88,
                        categoryPercentage: 0.62
                    }
                ];
            }

            function chartOptions() {
                return {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        position: 'top',
                        align: 'end',
                        rtl: true,
                        labels: {
                            fontFamily: 'iransans, Tahoma, sans-serif',
                            fontColor: textColor,
                            boxWidth: 12,
                            padding: 16
                        }
                    },
                    tooltips: {
                        rtl: true,
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(16, 22, 40, 0.94)',
                        titleFontFamily: 'iransans, Tahoma, sans-serif',
                        bodyFontFamily: 'iransans, Tahoma, sans-serif',
                        callbacks: {
                            label: function (tooltipItem, data) {
                                var datasetLabel = data.datasets[tooltipItem.datasetIndex].label || '';
                                var value = tooltipItem.yLabel || 0;
                                return datasetLabel + ': ' + Number(value).toLocaleString('fa-IR') + ' تومان';
                            }
                        }
                    },
                    scales: {
                        xAxes: [{
                            stacked: false,
                            gridLines: { display: false },
                            ticks: {
                                fontColor: mutedColor,
                                fontFamily: 'iransans, Tahoma, sans-serif',
                                fontSize: 10,
                                maxRotation: 0,
                                autoSkip: true,
                                maxTicksLimit: tickLimit(chartData.labels)
                            }
                        }],
                        yAxes: [{
                            stacked: false,
                            gridLines: {
                                color: gridColor,
                                zeroLineColor: gridColor,
                                drawBorder: false
                            },
                            ticks: {
                                beginAtZero: true,
                                fontColor: mutedColor,
                                fontFamily: 'iransans, Tahoma, sans-serif',
                                callback: formatAxisValue
                            }
                        }]
                    }
                };
            }

            function createChart() {
                return new Chart(canvas.getContext('2d'), {
                    type: chartType,
                    data: {
                        labels: chartData.labels,
                        datasets: datasetsFor(chartType)
                    },
                    options: chartOptions()
                });
            }

            var salesChart = createChart();

            function setActiveType(type) {
                document.querySelectorAll('#salesChartTypes [data-type]').forEach(function (item) {
                    item.classList.toggle('is-active', item.getAttribute('data-type') === type);
                });
            }

            function applyChartType(type) {
                chartType = type === 'line' ? 'line' : 'bar';
                localStorage.setItem('admin-sales-chart-type', chartType);
                setActiveType(chartType);
                salesChart.destroy();
                salesChart = createChart();
            }

            function applyChartData(payload) {
                chartData.labels = payload.labels || [];
                chartData.paid = payload.paid_amounts || [];
                chartData.unpaid = payload.unpaid_amounts || [];

                salesChart.data.labels = chartData.labels;
                salesChart.data.datasets = datasetsFor(chartType);
                salesChart.options.scales.xAxes[0].ticks.maxTicksLimit = tickLimit(chartData.labels);
                salesChart.update();

                var paidTotal = document.getElementById('salesPaidTotal');
                var unpaidTotal = document.getElementById('salesUnpaidTotal');
                var paidCount = document.getElementById('salesPaidCount');
                var unpaidCount = document.getElementById('salesUnpaidCount');
                var rangeLabel = document.getElementById('salesChartRangeLabel');

                if (paidTotal) paidTotal.textContent = formatNumber(payload.paid_total);
                if (unpaidTotal) unpaidTotal.textContent = formatNumber(payload.unpaid_total);
                if (paidCount) paidCount.textContent = formatNumber(payload.paid_count);
                if (unpaidCount) unpaidCount.textContent = formatNumber(payload.unpaid_count);
                if (rangeLabel && payload.range_label) rangeLabel.textContent = payload.range_label;
            }

            setActiveType(chartType);

            document.getElementById('salesChartTypes').addEventListener('click', function (event) {
                var button = event.target.closest('[data-type]');
                if (!button) {
                    return;
                }
                applyChartType(button.getAttribute('data-type'));
            });

            document.getElementById('salesChartRanges').addEventListener('click', function (event) {
                var button = event.target.closest('[data-range]');
                if (!button) {
                    return;
                }

                document.querySelectorAll('#salesChartRanges .admin-chart-range').forEach(function (item) {
                    item.classList.toggle('is-active', item === button);
                });

                var range = button.getAttribute('data-range');
                var url = chartUrl + (chartUrl.indexOf('?') === -1 ? '?' : '&') + 'sales_range=' + encodeURIComponent(range) + '&chart=1';

                fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(function (response) {
                    if (!response.ok) {
                        throw new Error('chart request failed');
                    }
                    return response.json();
                }).then(applyChartData).catch(function () {});
            });
        })();
    </script>
@endpush
@endif
