<?php

namespace App\Modules\Order\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Modules\Order\DTO\OrderShippingStatusDTO;
use App\Modules\Order\Entities\OrderShippingStatus;
use App\Modules\Order\Filters\OrderShippingStatusFilter;
use App\Modules\Order\Http\Requests\OrderShippingStatusRequest;
use App\Modules\Order\Services\OrderShippingStatusService;

class OrderShippingStatusController extends Controller
{
    protected $orderShippingStatusService;
    public function __construct(OrderShippingStatusService $orderShippingStatusService)
    {
        $this->orderShippingStatusService = $orderShippingStatusService;
    }
    /**
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $query = OrderShippingStatus::query();
        if ($request->has(['title'])) {
            $filters = [
                'title' => $request->input('title'),
            ];
            $query = app(OrderShippingStatusFilter::class)->apply($query, $filters);
        }
        $statuses = $query->orderByDesc('id')->paginate(20);
        return view('admin.order.order-shipping-status.index', compact('statuses'));

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $brands = $this->orderShippingStatusService->findAll();
        return view('admin.order.order-shipping-status.create', compact('brands'));

    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(OrderShippingStatusRequest $request)
    {

        $this->orderShippingStatusService->create(OrderShippingStatusDTO::fromRequest($request));
        return redirect()->route('admin.order-shipping-status.index', ['page' => $request->get('fallback_page',1)])
            ->with('success', 'برند محصول جدید اضافه شد.');
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(int $id)
    {
        $data = OrderShippingStatus::findOrfail($id);
        return view('admin.order.order-shipping-status.edit', compact('data'));

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(OrderShippingStatusRequest $request, $id)
    {
        $this->orderShippingStatusService->update($id, OrderShippingStatusDTO::fromRequest($request));
        return redirect()->route('admin.order-shipping-status.index', ['page' => $request->get('fallback_page',1)])
            ->with('success', 'برند محصول ویرایش شد.');

    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $this->orderShippingStatusService->deleteOne($id);
            return Redirect::back()->with('success', 'آیتم موردنظر با موفقیت حذف شد');
        } catch (\Exception $err) {
            return Redirect::back()
                ->with('error', $err->getMessage());
        }
    }

}
