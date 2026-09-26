<?php

namespace App\Modules\Order\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Modules\Location\Entities\State;
use App\Modules\Order\Entities\Basket;
use App\Modules\Order\Exports\BasketExport;
use App\Modules\Order\Exports\OrderExport;
use App\Modules\Order\Services\BasketAdminService;


class BasketController extends Controller
{
    protected $BasketAdminService;
    public function __construct(BasketAdminService $BasketAdminService)
    {
        $this->BasketAdminService = $BasketAdminService;
    }

    public function index(Request $request)
    {
        $baskets = $this->BasketAdminService->getList($request);
        $states = State::all();
        return view('admin.order.basket.index', compact('baskets','states'));
    }
    public function detail($id){
        $basket = Basket::findOrFail($id);
        $data = $this->BasketAdminService->getDetailData($basket);

        return view('admin.order.basket.detail', compact('basket','data'));
    }

    public function getCities(Request $request){
        return $this->BasketAdminService->getCities($request);
    }
    public function getUsers(Request $request){
        return $this->BasketAdminService->getUsers($request);
    }
    public function getProducts(Request $request){
        return $this->BasketAdminService->getProducts($request);
    }
    public function export(Request $request)
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '360000');
        return Excel::download(new BasketExport($request), 'baskets.xlsx');
    }

}
