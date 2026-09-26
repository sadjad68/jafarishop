<?php

namespace App\Http\Controllers\Panel;

use App\Library\Assistant\Modules\V1\Contact;
use App\Library\Assistant\Modules\V1\Seo;
use App\Library\NumberHelper;
use App\Library\SiteHelper;
use App\Library\YearHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use App\Modules\General\Helper\Sms;
use App\Modules\Order\Http\Requests\OrderImageRequest;
use App\Modules\Order\Services\OrderService;
use App\Modules\User\Entities\User;

class PanelController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        return view('pages.panel.dashboard.index',compact('user'));
    }
    public function profile()
    {
        $year = intval(NumberHelper::persian2LatinDigit(jdate('Y',Carbon::now()->timestamp)));
        $last = $year;
        $first = $year - 100;
        $years = YearHelper::generateNumbersBetween($first,$last);
        $months = [
            '01' => ['name' => 'فروردین', 'max' => '31'],
            '02' => ['name' => 'اردیبهشت', 'max' => '31'],
            '03' => ['name' => 'خرداد', 'max' => '31'],
            '04' => ['name' => 'تیر', 'max' => '31'],
            '05' => ['name' => 'مرداد', 'max' => '31'],
            '06' => ['name' => 'شهریور', 'max' => '31'],
            '07' => ['name' => 'مهر', 'max' => '30'],
            '08' => ['name' => 'آبان', 'max' => '30'],
            '09' => ['name' => 'آذر', 'max' => '30'],
            '10' => ['name' => 'دی', 'max' => '30'],
            '11' => ['name' => 'بهمن', 'max' => '30'],
            '12' => ['name' => 'اسفند', 'max' => '29'],
        ];
        return view('pages.panel.edit-information.index',compact('years','months'));
    }
    public function editProfile(Request $request){
        $input = $request->all();
        $input['mobile'] = NumberHelper::persian2LatinDigit($input['mobile']);
        $user = Auth::user();
        $check_mobile = Auth::user()->mobile;

        if (!empty($input['month']) || !empty($input['day']) || !empty($input['year'])) {
            if (empty($input['month']) || empty($input['day']) || empty($input['year'])) {
                return Redirect::back()->with('error', 'لطفاً تاریخ تولد (روز، ماه و سال) را به صورت کامل وارد کنید.');
            }
            if (!is_numeric($input['month']) || !is_numeric($input['day']) || !is_numeric($input['year'])) {
                return Redirect::back()->with('error', 'فرمت تاریخ تولد معتبر نیست.');
            }
            try {
                $year = (int) $input['year'];
                $month = (int) $input['month'];
                $day = (int) $input['day'];
                $s = jmktime(0, 0, 0, $month, $day, $year);
                if (!$s) {return Redirect::back()->with('error', 'تاریخ وارد شده معتبر نمی‌باشد.');}
                $input['birthday'] = Carbon::createFromTimestamp($s);
                $formattedDateTime = Carbon::now()->format('Y-m-d');

                if ($input['birthday']->format('Y-m-d') > $formattedDateTime) {
                    return Redirect::back()->with('error', 'تاریخ تولد نمی تواند بزرگتر از امروز باشد');
                }
            } catch (\Throwable $e) {
                return Redirect::back()->with('error', 'خطایی در پردازش تاریخ رخ داده است.');
            }
        } else {
            unset($input['birthday']);
        }
        $user->update($input);
        if ($check_mobile != $input['mobile']){
            Auth::logout();
            return redirect(route('auth.index'))->with('success', 'لطفا با شماره تلفن جدید خود وارد شوید!');
        }
        return Redirect::back()
            ->with('success', 'با موفقیت ویرایش شد');
    }
    public function storeImage(OrderImageRequest $request ,$id){
        $order = OrderService::findById($id);
        if ($order->user_id !=  Auth::id()){
            return redirect('/')->with('error','این فاکتور متعلق به شما نیست');

        }
        $isCardToCard = @$order->bank->bank_type === 'cardtocard' && $order->order_status == "paying";
        if ($order->order_status !=  "deposit_paid" && !$isCardToCard){
            return redirect('/')->with('info','برای اطلاعات بیشتر با پشتیبانی تماس بگیرید');

        }
        OrderService::storeImage($order, $request->file('file'));
        $order->update([
        'order_status'=>"wait_for_verification"
        ]);
        return redirect()->route('panel.order-detail',['id'=>$order->id]);
    }

}
