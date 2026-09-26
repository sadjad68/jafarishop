<?php

namespace App\Modules\User\Http\Controllers;

use App\Modules\General\Helper\HamgamanSms;
use App\Modules\User\Entities\User;
use Illuminate\Routing\Controller;

use App\Modules\General\Helper\NumberHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class AuthController extends Controller
{
    public function login()
    {
        return view('admin.user.auth.admin.login');
    }

    public function postLogin(Request $request)
    {
        $input = $request->all();
        $check = Auth::attempt([
            'email' => $input['email'],
            'password' => NumberHelper::persian2LatinDigit($input['password']),
            'deleted_at' => null
        ]);

        if ($check) {
            return redirect('/admin')->with('success', 'خوش آمدید.');
        } else {
            return redirect('/admin/login')->with('error', 'ایمیل یا رمزعبور صحیح نیست.');
        }
    }

    public function logout()
    {
        Session::flush();

        Auth::logout();

        return redirect('admin/login')->with('success', 'خوش آمدید.منتظر ورود دوباره شماییم!');
    }

    public function dashboard()
    {

        return view('admin.admin.dashboard');

    }

    public function showChangePasswordForm()
    {
        $this->clearPasswordResetSession();

        return view('admin.user.auth.admin.forgot-password');
    }

    public function sendPasswordResetCode(Request $request)
    {
        $mobile = NumberHelper::persian2LatinDigit(trim($request->input('mobile', '')));

        if (!strlen($mobile)) {
            return redirect()->route('admin.change-password')
                ->with('error', 'لطفا شماره موبایل را وارد کنید.');
        }

        $user = $this->findAdminByMobile($mobile);

        if (!$user) {
            return redirect()->route('admin.change-password')
                ->with('error', 'شماره موبایل یافت نشد یا دسترسی ادمین ندارد.');
        }

        $code = random_int(100000, 999999);
        $user->update(['confirm_code' => $code]);

        $sms = new HamgamanSms();
        $message = "کد تایید بازیابی رمز عبور پنل مدیریت:\n{$code}";
        $response = $sms->sendSms($message, $user->mobile);
        \Log::info($response);

        if ($response['success'] == false) {
            return redirect()->route('admin.change-password')
                ->with('error', 'خطایی در ارسال کد تایید رخ داد');
        }

        Session::put('admin_password_reset_user_id', $user->id);

        return redirect()->route('admin.change-password.verify')
            ->with('success', 'کد تایید برای شماره شما ارسال شد');
    }

    public function showVerifyCodeForm()
    {
        $user = $this->getPasswordResetUser();

        if (!$user) {
            return redirect()->route('admin.change-password')
                ->with('error', 'لطفا ابتدا شماره موبایل خود را وارد کنید.');
        }

        return view('admin.user.auth.admin.verify-code', [
            'maskedMobile' => $this->maskMobile($user->mobile),
        ]);
    }

    public function verifyPasswordResetCode(Request $request)
    {
        $user = $this->getPasswordResetUser();

        if (!$user) {
            return redirect()->route('admin.change-password')
                ->with('error', 'لطفا ابتدا شماره موبایل خود را وارد کنید.');
        }

        $code = NumberHelper::persian2LatinDigit(trim($request->input('code', '')));

        if (!strlen($code)) {
            return redirect()->route('admin.change-password.verify')
                ->with('error', 'لطفا کد تایید را وارد کنید.');
        }

        if ((string) $user->confirm_code !== $code) {
            return redirect()->route('admin.change-password.verify')
                ->with('error', 'کد تایید صحیح نیست.');
        }

        Session::put('admin_password_reset_verified', true);

        return redirect()->route('admin.change-password.reset');
    }

    public function showResetPasswordForm()
    {
        $user = $this->getPasswordResetUser();

        if (!$user || !Session::get('admin_password_reset_verified')) {
            return redirect()->route('admin.change-password')
                ->with('error', 'لطفا ابتدا کد تایید را وارد کنید.');
        }

        return view('admin.user.auth.admin.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $user = $this->getPasswordResetUser();

        if (!$user || !Session::get('admin_password_reset_verified')) {
            return redirect()->route('admin.change-password')
                ->with('error', 'لطفا ابتدا کد تایید را وارد کنید.');
        }

        $request->validate([
            'password' => 'required|min:6',
            're_password' => 'required|same:password|min:6',
        ], [
            'password.required' => 'رمز عبور اجباری است.',
            'password.min' => 'حداقل ۶ کاراکتر برای رمز عبور وارد کنید',
            're_password.required' => 'تکرار رمز عبور اجباری است.',
            're_password.same' => 'تکرار رمز عبور صحیح نمیباشد',
            're_password.min' => 'حداقل ۶ کاراکتر برای تکرار رمز عبور وارد کنید',
        ]);

        $user->update([
            'password' => bcrypt(NumberHelper::persian2LatinDigit($request->input('password'))),
            'confirm_code' => null,
        ]);

        $this->clearPasswordResetSession();

        return redirect()->route('admin.login')
            ->with('success', 'رمز عبور با موفقیت تغییر کرد. اکنون می‌توانید وارد شوید.');
    }

    private function findAdminByMobile(string $mobile): ?User
    {
        return User::where('mobile', $mobile)
            ->whereNull('deleted_at')
            ->whereHas('userTypes', function ($query) {
                $query->where('type', 'Admin');
            })
            ->first();
    }

    private function getPasswordResetUser(): ?User
    {
        $userId = Session::get('admin_password_reset_user_id');

        if (!$userId) {
            return null;
        }

        return User::where('id', $userId)
            ->whereNull('deleted_at')
            ->whereHas('userTypes', function ($query) {
                $query->where('type', 'Admin');
            })
            ->first();
    }

    private function clearPasswordResetSession(): void
    {
        Session::forget(['admin_password_reset_user_id', 'admin_password_reset_verified']);
    }

    private function maskMobile(string $mobile): string
    {
        if (strlen($mobile) < 7) {
            return $mobile;
        }

        return substr($mobile, 0, 4) . '***' . substr($mobile, -3);
    }
}
