<?php

namespace App\Modules\Order\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use App\Modules\General\Helper\NumberHelper;
use App\Modules\Order\Entities\Bank;
use App\Modules\Order\Services\BankService;


class BankController extends Controller
{
    protected $bankService;

    public function __construct(BankService $bankService)
    {
        $this->bankService = $bankService;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $banks = Bank::orderBy('sort', 'ASC')->orderBy('id', 'ASC')->get();
        return view('admin.order.bank.index', compact('banks'));

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data = Bank::findOrfail($id);
        $bank_fields = Config::get('order.my_banks')[$data->bank_type];
        return view('admin.order.bank.edit',
            compact('data','bank_fields'));

    }

    public function update(Request $request, $id)
    {
        $request->merge([
            'gateway_tariff' => NumberHelper::persian2LatinDigit($request->input('gateway_tariff', '0')),
        ]);

        $request->validate([
            'gateway_tariff' => 'nullable|integer|min:0|max:100',
        ], [
            'gateway_tariff.max' => 'تعرفه درگاه نمی‌تواند بیشتر از ۱۰۰ باشد.',
            'gateway_tariff.integer' => 'تعرفه درگاه باید عدد باشد.',
            'gateway_tariff.min' => 'تعرفه درگاه نمی‌تواند منفی باشد.',
        ]);

        $input = $request->all();
        $input['status'] = $request->get('status') ? 1 : 0;
        $input['admin_test'] = $request->get('admin_test') ? 1 : 0;

        $this->bankService->update($id, $input);
        return redirect()->route('admin.bank.index', ['page' => $request->get('fallback_page',1)])
            ->with('success', 'درگاه ویرایش شد.');
    }

    public function updateSort(Request $request)
    {
        $this->bankService->updateSort($request->order ?? []);
        echo 'با موفقیت ذخیره شد.';
    }

}
