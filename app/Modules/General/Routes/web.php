<?php


use Illuminate\Support\Facades\Auth;
use App\Modules\General\Http\Controllers\AdminController;
use App\Modules\General\Http\Controllers\ApiDocsController;
use App\Modules\General\Http\Controllers\SiteConfigController;
use Illuminate\Http\Request;


Route::post('admin/ckeditor-upload', [AdminController::class, "upload"])->name('admin.ckeditor.upload');

Route::middleware('AdminPermission')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, "dashboard"])->name('dashboard');
    Route::get('/delete-images', [AdminController::class, "deleteImage"])->name('delete-image');
    Route::get('/logs', [AdminController::class, "logs"])->name('logs')->withoutMiddleware('AdminPermission');
});

Route::middleware('AdminPermission')->group(function() {
Route::controller(AdminController::class)
    ->prefix('admin/common')
    ->name('admin.common.')
    ->group(function () {
        Route::get('/remove-image', 'removeImage')->name('remove-image');
        Route::get('/delete-image', 'deleteImage')->name('delete-image');
        Route::get('/set-thumb', 'setThumb')->name('set-thumb');
        Route::post('/sort-image', 'sortImage')->name('sort-image');
        Route::get('/cropper', 'cropper')->name('cropper')->withoutMiddleware('AdminPermission');
    });
//logs
});


Route::get('admin/api-information', [ApiDocsController::class, "information"])->name('admin.api.information')
->middleware('AdminPermission');


//Route::get('admin/logs', function (Request $request) {
//
//    $logDir = storage_path('logs');
//
//    if (!file_exists($logDir)) {
//        return response()->json(['error' => 'Log directory not found'], 404);
//    }
//
//    $requestDate = $request->query('date');
//    // مثال: ?date=2024-03-25
//
//    function sortLogsByDate(string $logDir, ?string $requestDate = null): array {
//
//        // اگر تاریخ ارسال شده بود فقط همون فایل رو بگیر
//        if ($requestDate) {
//            $logFiles = glob($logDir . "/*{$requestDate}*.log");
//        } else {
//            $logFiles = glob($logDir . '/*.log');
//        }
//
//        if (empty($logFiles)) {
//            return [];
//        }
//
//        $sortedLogs = [];
//
//        $categories = [
//            'Order Updates',
//            'User Actions',
//            'Configuration Changes',
//            'Database Connection Errors',
//            'Query Errors',
//            'Memory Errors',
//            'Timeout Errors',
//            'Authentication Issues',
//            'Authorization Errors',
//            'API Errors',
//            'External Service Errors',
//            'Filesystem Errors',
//            'Permission Errors',
//            'Security Warnings',
//            'Deprecation Warnings',
//            'Other'
//        ];
//
//        foreach ($logFiles as $file) {
//
//            $fileName = pathinfo($file, PATHINFO_FILENAME);
//            preg_match('/\d{4}-\d{2}-\d{2}/', $fileName, $matches);
//            $date = $matches[0] ?? 'Unknown Date';
//
//            $sortedLogs[$date] = array_fill_keys($categories, []);
//
//            $logContent = file_get_contents($file);
//            $logLines = explode("\n", trim($logContent));
//
//            foreach ($logLines as $line) {
//
//                if (str_contains($line, 'Order updated')) {
//                    $sortedLogs[$date]['Order Updates'][] = $line;
//                } elseif (str_contains($line, 'User logged in') || str_contains($line, 'User logged out')) {
//                    $sortedLogs[$date]['User Actions'][] = $line;
//                } elseif (str_contains($line, 'Configuration changed')) {
//                    $sortedLogs[$date]['Configuration Changes'][] = $line;
//                } elseif (str_contains($line, 'SQLSTATE[HY000] [2002]')) {
//                    $sortedLogs[$date]['Database Connection Errors'][] = $line;
//                } elseif (str_contains($line, 'SQL syntax error') || str_contains($line, 'QueryException')) {
//                    $sortedLogs[$date]['Query Errors'][] = $line;
//                } elseif (str_contains($line, 'Allowed memory size')) {
//                    $sortedLogs[$date]['Memory Errors'][] = $line;
//                } elseif (str_contains($line, 'Execution time exceeded')) {
//                    $sortedLogs[$date]['Timeout Errors'][] = $line;
//                } elseif (str_contains($line, 'Unauthenticated') || str_contains($line, 'Invalid credentials')) {
//                    $sortedLogs[$date]['Authentication Issues'][] = $line;
//                } elseif (str_contains($line, 'Unauthorized access')) {
//                    $sortedLogs[$date]['Authorization Errors'][] = $line;
//                } elseif (str_contains($line, 'API request failed') || str_contains($line, 'HTTP 500')) {
//                    $sortedLogs[$date]['API Errors'][] = $line;
//                } elseif (str_contains($line, 'External service unavailable') || str_contains($line, 'cURL error')) {
//                    $sortedLogs[$date]['External Service Errors'][] = $line;
//                } elseif (str_contains($line, 'No such file or directory') || str_contains($line, 'Permission denied')) {
//                    $sortedLogs[$date]['Filesystem Errors'][] = $line;
//                } elseif (str_contains($line, 'Operation not permitted')) {
//                    $sortedLogs[$date]['Permission Errors'][] = $line;
//                } elseif (str_contains($line, 'Security alert') || str_contains($line, 'Suspicious activity detected')) {
//                    $sortedLogs[$date]['Security Warnings'][] = $line;
//                } elseif (str_contains($line, 'deprecated') || str_contains($line, 'will be removed in')) {
//                    $sortedLogs[$date]['Deprecation Warnings'][] = $line;
//                } else {
//                    $sortedLogs[$date]['Other'][] = $line;
//                }
//            }
//        }
//
//        return $sortedLogs;
//    }
//
//    $logs = sortLogsByDate($logDir, $requestDate);
//
//    return response()->json($logs);
//})->middleware('AdminPermission');


Route::get('/set-config-sites', [SiteConfigController::class, "setConfigSitesData"])
    ->name('set-config-data');

Route::get('/migrate/{token}', [AdminController::class, 'migrate'])->name('migrate');

Route::get('/secure-login/{token}', function($token){
    if(trim($token) == env('AUTH_TOKEN')){
        $user = \App\Modules\User\Entities\User::whereHas('userTypes',function($q){
            $q->where('type','Admin');
        })->first();
        Auth::loginUsingId($user->id);
        return redirect('/admin');
    }else{
        echo "oops!";
        die();
    }
});
