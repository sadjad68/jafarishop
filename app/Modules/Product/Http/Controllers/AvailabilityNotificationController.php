<?php

namespace App\Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Library\SiteHelper;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Modules\Product\Entities\ProductNotification;
use App\Modules\Product\Exports\ProductNotificationExport;
use App\Modules\Product\Services\NotificationService;
use App\Modules\Setting\Entities\Setting;

class AvailabilityNotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $query = ProductNotification::with([
            'user',
            'product',
            'variant.specifications',
            'variant.specification',
        ])
            ->availability();

        if ($request->filled('search')) {
            $searchDastan = $request->input('search');
            $query->whereHas('product', function ($q) use ($searchDastan) {
                $q->where('title', 'LIKE', "%{$searchDastan}%");
            });
        }

        $items = $query->orderByDesc('id')->paginate(20)->withQueryString();

        return view('admin.product.notification.availability', compact('items'));
    }


    public function bulkSendSms(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:product_notifications,id',
        ]);
        \Log::info('BULK SMS CONTROLLER HIT', $request->all());

        $activeNotifications = (int)(Setting::where('key', 'active_notifications')->value('value') ?? 0);
        if ($activeNotifications !== 1) {
            return redirect()->back()->with('warning', 'تنظیمات مرتبط با موجود شد خبرم کن، غیرفعال است!');
        }

        $site = SiteHelper::getInformation();
        $sentCount = $this->notificationService->sendBulkAvailabilityArray(
            $request->input('ids'),
            $site['template_url'] ?? ''
        );

        if ($sentCount === 0) {
            return redirect()->back()->with('warning', 'هیچ پیامکی ارسال نشد. (بررسی کنید محصولات موجودی داشته باشند)');
        }

        return redirect()->back()->with('success', "$sentCount پیامک موجودی با موفقیت ارسال شد.");
    }

    public function destroy($id)
    {
        $notification = ProductNotification::availability()->findOrFail($id);
        $notification->delete();

        return back()->with('success', 'آیتم حذف شد.');
    }

    public function export(Request $request)
    {
        $query = ProductNotification::with(['user', 'product', 'variant'])->availability();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%");
            });
        }

        $items = $query->orderByDesc('id')->get();
        return Excel::download(new ProductNotificationExport($items), 'availability_requests.xlsx');
    }
}
