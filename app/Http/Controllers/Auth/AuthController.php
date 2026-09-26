<?php

namespace App\Http\Controllers\Auth;

use App\Library\Assistant\Modules\V1\Contact;
use App\Library\Assistant\Modules\V1\Seo;
use App\Library\SiteHelper;
use App\Modules\General\Helper\BaleOtp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use App\Modules\General\Helper\NumberHelper;
use App\Modules\General\Helper\Sms;
use App\Modules\Order\Entities\Basket;
use App\Modules\Order\Services\BankService;
use App\Modules\Order\Services\BasketService;
use App\Modules\User\Entities\User;
use App\Modules\User\Services\UserService;

class AuthController extends Controller
{
    public function index()
    {
        try {
            return view('pages.auth.login');
        } catch (\Exception $err) {
            Log::info($err->getMessage());
            return back()->with('error', $err->getMessage());
        }
    }

    public function login(Request $request)
    {
        try {
            $input = $request->only('name', 'mobile', 'url');
            $mobile = $input['mobile'];
            if (! strlen($mobile)) {
                return back()->with(['error' => 'لطفا شماره موبایل رو وارد کنید']);
            }
            $user = UserService::findByMobile($mobile);
            $code = rand(1000, 9999);
            if (! $user) {
                if (! strlen($input['name'])) {
                    return back()->with(['error' => 'نام و نام خانوادگی الزامیست']);
                }
                $user = UserService::createFromSite($request);
            }

            $user->update(['confirm_code' => $code]);
            $kavenegar = new Sms();
            $result = $kavenegar->sendLookup(
                'verify',
                [
                    'token' => $code,
                ],
                $user->mobile
            );
            if ($result instanceof RedirectResponse) {
                return $result;
            }

            $otpDelivered = $result !== false;
            if (! $otpDelivered) {
                $otpDelivered = (new BaleOtp())->send($mobile, $code);
            }

            if (! $otpDelivered && app()->environment('local')) {
                Log::info("[LOCAL] Login OTP for {$mobile}: {$code}");
            }

            return redirect(route('auth.mobile-code', [
                'mobile' => $mobile,
                'url' => \request()->get('url'),
            ]));
        } catch (\Exception $err) {
            Log::info($err->getMessage());
            return back()->with('error', $err->getMessage());
        }
    }

    public function checkUserExisting(Request $request)
    {
        try {
            $user = UserService::findByMobile($request->mobile);
            if ($user) {
                return response()->json(true);
            }

            return response()->json(false);
        } catch (\Exception $err) {
            Log::info($err->getMessage());
            return response()->json(['error' => $err->getMessage()], 500);
        }
    }

    public function getCode()
    {
        try {
            return view('pages.auth.confirm-code');
        } catch (\Exception $err) {
            Log::info($err->getMessage());
            return back()->with('error', $err->getMessage());
        }
    }

    public function postCode()
    {
        try {
            $mobile = NumberHelper::persian2LatinDigit(trim((string) \request()->get('mobile')));
            $code = NumberHelper::persian2LatinDigit(trim((string) \request()->get('code')));
            $user = UserService::findByMobileAndCode($mobile, $code);
            if ($user) {
                Auth::loginUsingId($user->id);
                if (\request()->get('url')) {
                    $url = url(\request()->get('url'));
                } else {
                    $url = route('panel.dashboard');
                }
                BasketService::findUserBasketAndUpdateBaskets();

                if (\request()->expectsJson()) {
                    return response()->json(['redirect' => $url]);
                }

                return redirect($url)->with('success', 'ورود با موفقیت انجام شد');
            }

            if (\request()->expectsJson()) {
                return response()->json(['message' => 'کد نادرست است'], 422);
            }

            return back()->with('error', 'کد نادرست است');
        } catch (\Exception $err) {
            Log::info($err->getMessage());

            if (\request()->expectsJson()) {
                return response()->json(['message' => $err->getMessage()], 500);
            }

            return back()->with('error', $err->getMessage());
        }
    }

    public function logout()
    {
        try {
            Auth::logout();

            return redirect('/')->with('success', 'خوش آمدید, منتظر ورود دوباره شماییم!');
        } catch (\Exception $err) {
            Log::info($err->getMessage());
            return back()->with('error', $err->getMessage());
        }
    }
}
