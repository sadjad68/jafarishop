<?php

namespace App\Modules\Order\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use App\Modules\General\Helper\NumberHelper;
use App\Modules\Order\Entities\Bank;
use App\Modules\User\Entities\UserType;

class BankService
{
    public static function findAllActive()
    {
        $admin_test = false;
        if (Auth::check()) {
            $is_admin = UserType::where('user_id', Auth::user()->id)->where('type', 'Admin')->first();
            if ($is_admin) {
                $admin_test = true;
            }
        }
        $query = Bank::orderBy('sort', 'ASC')->orderBy('id', 'ASC')->Active();
        if ($admin_test) {
            return $query->get();
        }

        return $query->where('admin_test', 0)->get();
    }

    public function update(int $id, $input)
    {
        $bank = Bank::findOrfail($id);
        $bankFields = config('order.my_banks.' . $bank->bank_type, []);
        $normalizedConfig = Arr::except($input, ['status', 'admin_test', 'gateway_tariff', '_token', 'fallback_page']);

        foreach ($bankFields as $fieldKey => $fieldConfig) {
            if (($fieldConfig['type'] ?? null) === 'checkbox') {
                $normalizedConfig[$fieldKey] = isset($input[$fieldKey]) ? 1 : 0;
            }
            if ($fieldKey === 'reservation_expire_minutes') {
                $raw = $input[$fieldKey] ?? null;
                if ($raw === null || $raw === '') {
                    $normalizedConfig[$fieldKey] = null;
                } else {
                    $minutes = intval(NumberHelper::persian2LatinDigit((string) $raw));
                    $normalizedConfig[$fieldKey] = $minutes > 0 ? $minutes : null;
                }
            }
        }

        $bank->update([
            'status' => $input['status'] ?? 1,
            'admin_test' => $input['admin_test'] ?? 0,
            'gateway_tariff' => intval(NumberHelper::persian2LatinDigit($input['gateway_tariff'] ?? 0)),
            'config' => json_encode($normalizedConfig),
        ]);
    }

    public function updateSort(array $order): void
    {
        foreach ($order as $key => $id) {
            $bank = Bank::findOrFail($id);
            $bank->sort = $key + 1;
            $bank->save();
        }
    }
}
