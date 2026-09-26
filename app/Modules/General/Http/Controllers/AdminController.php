<?php

namespace App\Modules\General\Http\Controllers;

use App\Modules\General\Helper\CacheHelper;
use App\Modules\General\Helper\FileManager;
use App\Modules\Order\Entities\Order;
use App\Modules\Product\Entities\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;
class AdminController
{
    public function dashboard(Request $request)
    {
        $salesRange = $this->normalizeSalesRange($request->get('sales_range'));

        try {
            $hasOrders = Order::query()->exists();
        } catch (\Throwable $e) {
            $hasOrders = false;
        }

        $salesChart = $hasOrders ? $this->salesChartData($salesRange) : null;

        if ($hasOrders && ($request->boolean('chart') || $request->wantsJson())) {
            return response()->json($salesChart);
        }

        return view('admin.dashboard.index', [
            'salesChart' => $salesChart,
            'salesRange' => $salesRange,
            'hasOrders' => $hasOrders,
        ]);
    }

    public function migrate(string $token)
    {
        $expected = (string) env('AUTH_TOKEN', '');
        if ($expected === '' || !hash_equals($expected, $token)) {
            abort(403, 'Unauthorized.');
        }

        Artisan::call('migrate', [
            '--force' => true,
        ]);

        return response(Artisan::output() ?: 'Nothing to migrate.', 200)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    private function normalizeSalesRange($range): string
    {
        $range = (string) $range;

        return in_array($range, ['7', '30', '60', '365', 'all'], true) ? $range : '30';
    }

    private function salesChartData(string $range = '30'): array
    {
        $paidStatuses = ['paid', 'deposit_paid'];
        $unpaidStatuses = ['unpaid', 'paying', 'wait_for_verification'];
        $statuses = array_merge($paidStatuses, $unpaidStatuses);
        $labelsMap = [
            '7' => 'یک هفته اخیر',
            '30' => '۱ ماه اخیر',
            '60' => '۲ ماه اخیر',
            '365' => '۱ سال اخیر',
            'all' => 'کل روزها',
        ];

        $from = null;
        $groupBy = 'day';
        if ($range !== 'all') {
            $from = now()->subDays(((int) $range) - 1)->startOfDay();
        }

        try {
            $minDate = $from;
            if ($range === 'all') {
                $minDate = Order::query()
                    ->whereIn('order_status', $statuses)
                    ->min('created_at');
                $minDate = $minDate ? \Carbon\Carbon::parse($minDate)->startOfDay() : now()->startOfDay();
            }

            $spanDays = $minDate->copy()->startOfDay()->diffInDays(now()->endOfDay());
            $groupBy = 'day';
            if ($range === '365' || ($range === 'all' && $spanDays > 90)) {
                $groupBy = 'month';
            }
            if ($range === 'all' && $spanDays > 800) {
                $groupBy = 'year';
            }

            $byKey = $this->salesChartBuckets($minDate, $groupBy);

            $orders = Order::query()
                ->when($from, fn ($query) => $query->where('created_at', '>=', $from))
                ->whereIn('order_status', $statuses)
                ->get(['created_at', 'order_status', 'payment_price']);
        } catch (\Throwable $e) {
            $byKey = $this->salesChartBuckets(now()->subDays(29)->startOfDay(), 'day');
            $orders = collect();
        }

        foreach ($orders as $order) {
            if (!$order->created_at) {
                continue;
            }
            $key = $this->salesChartKey($order->created_at, $groupBy ?? 'day');
            if (!isset($byKey[$key])) {
                continue;
            }

            $amount = (int) str_replace(',', '', (string) $order->payment_price);
            if (in_array($order->order_status, $paidStatuses, true)) {
                $byKey[$key]['paid'] += $amount;
                $byKey[$key]['paid_count']++;
            } else {
                $byKey[$key]['unpaid'] += $amount;
                $byKey[$key]['unpaid_count']++;
            }
        }

        $labels = [];
        $paidAmounts = [];
        $unpaidAmounts = [];
        $paidTotal = 0;
        $unpaidTotal = 0;
        $paidCount = 0;
        $unpaidCount = 0;

        foreach ($byKey as $row) {
            $labels[] = $row['label'];
            $paidAmounts[] = $row['paid'];
            $unpaidAmounts[] = $row['unpaid'];
            $paidTotal += $row['paid'];
            $unpaidTotal += $row['unpaid'];
            $paidCount += $row['paid_count'];
            $unpaidCount += $row['unpaid_count'];
        }

        return [
            'range' => $range,
            'range_label' => $labelsMap[$range] ?? $labelsMap['30'],
            'labels' => $labels,
            'paid_amounts' => $paidAmounts,
            'unpaid_amounts' => $unpaidAmounts,
            'paid_total' => $paidTotal,
            'unpaid_total' => $unpaidTotal,
            'paid_count' => $paidCount,
            'unpaid_count' => $unpaidCount,
        ];
    }

    private function salesChartBuckets(\Carbon\Carbon $from, string $groupBy): array
    {
        $buckets = [];

        if ($groupBy === 'year') {
            $cursor = $from->copy()->startOfYear();
            $end = now()->startOfYear();
            while ($cursor->lte($end)) {
                $buckets[$cursor->format('Y')] = $this->emptySalesBucket(
                    function_exists('jdate') ? jdate('Y', $cursor->timestamp) : $cursor->format('Y')
                );
                $cursor->addYear();
            }

            return $buckets;
        }

        if ($groupBy === 'month') {
            $cursor = $from->copy()->startOfMonth();
            $end = now()->startOfMonth();
            while ($cursor->lte($end)) {
                $buckets[$cursor->format('Y-m')] = $this->emptySalesBucket(
                    function_exists('jdate') ? jdate('F Y', $cursor->timestamp) : $cursor->format('Y/m')
                );
                $cursor->addMonth();
            }

            return $buckets;
        }

        $cursor = $from->copy()->startOfDay();
        $end = now()->startOfDay();
        while ($cursor->lte($end)) {
            $buckets[$cursor->toDateString()] = $this->emptySalesBucket(
                function_exists('jdate') ? jdate('d F', $cursor->timestamp) : $cursor->format('m/d')
            );
            $cursor->addDay();
        }

        return $buckets;
    }

    private function emptySalesBucket(string $label): array
    {
        return [
            'label' => $label,
            'paid' => 0,
            'unpaid' => 0,
            'paid_count' => 0,
            'unpaid_count' => 0,
        ];
    }

    private function salesChartKey($date, string $groupBy): string
    {
        $date = \Carbon\Carbon::parse($date);

        if ($groupBy === 'year') {
            return $date->format('Y');
        }
        if ($groupBy === 'month') {
            return $date->format('Y-m');
        }

        return $date->toDateString();
    }

    public function ckeditor()
    {
        return view('admin.ckeditor');
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $types = ['gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];
            if(in_array(strtolower($request->file('upload')->getClientOriginalExtension()),$types))
            {
                $fileName = FileManager::uploadRaw($request->file('upload'), "content");
            }else{
                $fileName = FileManager::upload($request->file('upload'), "content");
            }

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = FileManager::serveFile(
                'uploads/content/' . $fileName, 'assets/notfounds/default.jpg'
            );

            $msg = 'تصویر با موفقیت بارگذاری شد.';
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
    }

    public function removeImage(Request $request)
    {
        $model = $request->get('model');
        $column = $request->get('name');
        $id = $request->get('id');
        $clear_cache = $request->get('clear_cache') ?? false;
        $x = $model::findOrFail($id);
        $x->update([
            $column => null
        ]);

        if($clear_cache){
            CacheHelper::clearCache();
        }
        return Redirect::back()->with('success', 'تصویر با موفقیت حذف شد');

    }

    public function deleteImage(Request $request)
    {
        $model = $request->get('model');
        $id = $request->get('id');
        if ($model == "App\Modules\Product\Entities\Image") {
            $image = $model::findOrFail($id);
            $model::destroy($id);
            $product = Product::findOrFail($image->product_id);
            if ($image->image == $product->image) {


                $product->update(
                    [
                        'image' => count($product->images) > 0 ? @$product->images[0]->image : null
                    ]
                );
            }

        } else {
            $model::destroy($id);
        }


        return Redirect::back()->with('success', 'تصویر با موفقیت حذف شد');

    }

    public function setThumb(Request $request)
    {
        $model = $request->get('model');
        $id = $request->get('id');
        $product = Product::findOrFail($request->get('product_id'));
        foreach ($product->images as $img) {
            $img->update([
                'thumbnail' => 0
            ]);
        }
        $thumb = $model::findOrFail($id);
        $thumb->update([
            'thumbnail' => 1
        ]);
        $product->update(
            [
                'image' => $thumb['image']
            ]
        );


        return Redirect::back()->with('success', 'تصویر با موفقیت حذف شد');

    }

    public function sortImage(Request $request)
    {
        if ($request->get('update') == "update") {
            $model = $request->get('model');
            $count = 1;
            if ($request->get('update') == 'update') {
                foreach ($request->get('arrayorder') as $idval) {

                    $sort = $model::find($idval);
                    $sort->sort = $count;
                    $sort->save();
                    $count++;
                }
                echo 'با موفقیت ذخیره شد.';
            }
        }

    }

    public function cropper(Request $request)
    {
//        dd($request->all());
        return view('admin.components.cropper.cropper');
    }
    public function apiInformation(){
        return view('admin.api.information');
    }
    public function logs(Request $request)
    {
        $request->validate([
            'date' => ['nullable', 'date_format:Y-m-d'],
        ]);

        $logDir = storage_path('logs');

        if (!File::exists($logDir)) {
            return response()->json([
                'error' => 'Log directory not found'
            ], 404);
        }

        $requestDate = $request->query('date');

        // اگر تاریخ ارسال شده فقط همون فایل رو بگیر
        if ($requestDate) {
            $logFiles = File::glob($logDir . "/*{$requestDate}*.log");

            if (empty($logFiles)) {
                return response()->json([
                    'error' => 'Log file not found for this date'
                ], 404);
            }
        } else {
            $logFiles = File::glob($logDir . '/*.log');
        }

        $categories = [
            'Order Updates',
            'User Actions',
            'Configuration Changes',
            'Database Connection Errors',
            'Query Errors',
            'Memory Errors',
            'Timeout Errors',
            'Authentication Issues',
            'Authorization Errors',
            'API Errors',
            'External Service Errors',
            'Filesystem Errors',
            'Permission Errors',
            'Security Warnings',
            'Deprecation Warnings',
            'Other'
        ];

        $sortedLogs = [];

        foreach ($logFiles as $file) {

            $fileName = pathinfo($file, PATHINFO_FILENAME);
            preg_match('/\d{4}-\d{2}-\d{2}/', $fileName, $matches);
            $date = $matches[0] ?? 'Unknown Date';

            if (!isset($sortedLogs[$date])) {
                $sortedLogs[$date] = array_fill_keys($categories, []);
            }

            $logContent = File::get($file);
            $logLines = explode("\n", trim($logContent));

            foreach ($logLines as $line) {

                if (str_contains($line, 'Order updated')) {
                    $sortedLogs[$date]['Order Updates'][] = $line;
                } elseif (str_contains($line, 'User logged in') || str_contains($line, 'User logged out')) {
                    $sortedLogs[$date]['User Actions'][] = $line;
                } elseif (str_contains($line, 'Configuration changed')) {
                    $sortedLogs[$date]['Configuration Changes'][] = $line;
                } elseif (str_contains($line, 'SQLSTATE[HY000] [2002]')) {
                    $sortedLogs[$date]['Database Connection Errors'][] = $line;
                } elseif (str_contains($line, 'SQL syntax error') || str_contains($line, 'QueryException')) {
                    $sortedLogs[$date]['Query Errors'][] = $line;
                } elseif (str_contains($line, 'Allowed memory size')) {
                    $sortedLogs[$date]['Memory Errors'][] = $line;
                } elseif (str_contains($line, 'Execution time exceeded')) {
                    $sortedLogs[$date]['Timeout Errors'][] = $line;
                } elseif (str_contains($line, 'Unauthenticated') || str_contains($line, 'Invalid credentials')) {
                    $sortedLogs[$date]['Authentication Issues'][] = $line;
                } elseif (str_contains($line, 'Unauthorized access')) {
                    $sortedLogs[$date]['Authorization Errors'][] = $line;
                } elseif (str_contains($line, 'API request failed') || str_contains($line, 'HTTP 500')) {
                    $sortedLogs[$date]['API Errors'][] = $line;
                } elseif (str_contains($line, 'External service unavailable') || str_contains($line, 'cURL error')) {
                    $sortedLogs[$date]['External Service Errors'][] = $line;
                } elseif (str_contains($line, 'No such file or directory') || str_contains($line, 'Permission denied')) {
                    $sortedLogs[$date]['Filesystem Errors'][] = $line;
                } elseif (str_contains($line, 'Operation not permitted')) {
                    $sortedLogs[$date]['Permission Errors'][] = $line;
                } elseif (str_contains($line, 'Security alert') || str_contains($line, 'Suspicious activity detected')) {
                    $sortedLogs[$date]['Security Warnings'][] = $line;
                } elseif (str_contains($line, 'deprecated') || str_contains($line, 'will be removed in')) {
                    $sortedLogs[$date]['Deprecation Warnings'][] = $line;
                } else {
                    $sortedLogs[$date]['Other'][] = $line;
                }
            }
        }

        return response()->json($sortedLogs);
    }
}
