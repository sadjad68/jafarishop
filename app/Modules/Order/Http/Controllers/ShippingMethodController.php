<?php

namespace App\Modules\Order\Http\Controllers;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use App\Modules\Location\Entities\City;
use App\Modules\Location\Entities\State;
use App\Modules\Order\DTO\ShippingMethodDTO;
use App\Modules\Order\Entities\ShippingMethod;
use App\Modules\Order\Filters\ShippingMethodFilter;
use App\Modules\Order\Http\Requests\ShippingMethodRequest;
use App\Modules\Order\Library\ChaparShipment;
use App\Modules\Order\Services\ShippingMethodService;
use Illuminate\Support\Facades\File;

class ShippingMethodController extends Controller
{
    protected $shippingMethodService;
    public function __construct(ShippingMethodService $shippingMethodService)
    {
        $this->shippingMethodService = $shippingMethodService;
    }
    /**
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $query = ShippingMethod::query();
        if ($request->has(['title'])) {
            $filters = [
                'title' => $request->input('title'),
            ];
            $query = app(ShippingMethodFilter::class)->apply($query, $filters);
        }
        $methods= $query->orderByDesc('id')->paginate(20);
        return view('admin.order.shipping-method.index', compact('methods'));

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $cities = City::all()->map(function ($city) {
            return [
                'id' => $city->id,
                'name' => $city->name,
                'checked' => false
            ];
        });
        $methods = Config::get('order.shipping_methods');

        return view('admin.order.shipping-method.create', compact('cities','methods'));

    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(ShippingMethodRequest $request)
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '360000');
        ini_set('upload_max_filesize', '64M');
        ini_set('post_max_size', '64M');
        $this->shippingMethodService->create(ShippingMethodDTO::fromRequest($request));
        return redirect()->route('admin.shipping-method.index', ['page' => $request->get('fallback_page',1)])
            ->with('success', 'روش ارسال جدید اضافه شد.');
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(int $id)
    {
        $data = ShippingMethod::findOrFail($id);

        $cities = City::all()->map(function ($city) use ($data) {
            return [
                'id' => $city->id,
                'name' => $city->name,
                'checked' => $data->cities->contains($city->id) ? true : false,
            ];
        });
        $methods = Config::get('order.shipping_methods');

        return view('admin.order.shipping-method.edit', compact('data','cities','methods'));

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(ShippingMethodRequest $request, $id)
    {
      ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '360000');
        ini_set('upload_max_filesize', '64M');
        ini_set('post_max_size', '64M');
        $this->shippingMethodService->update($id, ShippingMethodDTO::fromRequest($request));
        return redirect()->route('admin.shipping-method.index', ['page' => $request->get('fallback_page',1)])
            ->with('success', 'روش ارسال ویرایش شد.');

    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $this->shippingMethodService->deleteOne($id);
        return Redirect::back()->with('success', 'آیتم موردنظر با موفقیت حذف شد');
    }
public function chaparBranch(){

    $response = ChaparShipment::agent();
    $users = $response['objects']['user'];
    $cityNames = collect($users)->groupBy('city_name')->keys();

    return view('admin.order.shipping-method.branches', compact('cityNames'));
}
    public function sqlToJson()
    {
        $states = State::with('cities')->get();
        $result = [];

        foreach ($states as $state) {
            $result[] = [
                "id" => $state->id,
                "name" => $state->name,
                "chapar_id" => $state->chapar_id,
                "status" => $state->status,
                "deleted_at" => $state->deleted_at,
                "created_at" => $state->created_at,
                "updated_at" => $state->updated_at,
                "cities" => $state->cities->map(function ($city) {
                    return [
                        "id" => $city->id,
                        "name" => $city->name,
                        "chapar_id" => $city->chapar_id,
                        "state_id" => $city->state_id,
                        "status" => $city->status,
                        "deleted_at" => $city->deleted_at,
                        "created_at" => $city->created_at,
                        "updated_at" => $city->updated_at,
                    ];
                })->toArray()
            ];
        }

        return response()->json($result);
    }


}
