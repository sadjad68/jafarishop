<?php

namespace App\Modules\Setting\Services;

use Illuminate\Support\Facades\Log;
use App\Modules\Banner\Entities\Banner;
use App\Modules\General\Helper\FileManager;
use App\Modules\General\Helper\NumberHelper;
use App\Modules\General\Helper\Sms;
use App\Modules\General\Helper\TestImage;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\ProductCategory;
use App\Modules\Product\Services\ProductCategoryService;
use App\Modules\Service\Services\ServiceManager;
use App\Modules\Setting\Entities\Setting;
use Illuminate\Support\Facades\Redirect;
use App\Modules\Setting\Helper\MenuHelper;
use App\Modules\Setting\Helper\GtmSnippetValidator;

class SettingService
{
    public static function findAll($keys = [])
    {
        $setting = Setting::query();
        if (count($keys) > 0) {
            $setting->whereIn('key', $keys);
        }
        return $setting->get();
    }

    public static function getFormatSettings($query=[])
    {
        $data = self::findAll($query);
        $settings = [];
        $keysToCheck = ['video_cover','mobile_header_banner','desktop_header_banner'];
        foreach ($data as $row) {
            $fileUrl = null;
            $base_upload_folder = config('setting.base_upload_folder');
            if (in_array($row->key, $keysToCheck) && @$row->value != null) {
                if (file_exists(FileManager::publicFilePath($base_upload_folder . '/uploads/setting/' . @$row->value))) {
                    $fileUrl = FileManager::serveFile(
                        'uploads/setting/' . $row->value
                    );
                }
            }
            $settings[$row->key] = match ($row->type) {
                "input_file" => self::fileFormat($row),
                "input_file_about" => self::fileFormat($row),
                "array" => self::arrayFormat($row->value),
                "array_files" => self::arrayFileFormat($row->value),
                "work_hours" => self::jsonFormat($row->value),
                "menu" => self::jsonFormat($row->value),
                "footer" => self::jsonFormat($row->value),
                default => $row->value,
            };
            if (in_array($row->key, $keysToCheck)) {
                $settings[$row->key] = $fileUrl;
            }
        }
        return $settings;
    }

    public static function jsonFormat($value)
    {
        return json_decode($value, true);
    }

    public static function defaultMenuIconUrl(): string
    {
        return asset('assets/site/images/shop/mouse.svg');
    }

    public static function arrayFormat($value): array
    {
        return $value ?  explode("~~##", $value) : [];
    }

    public static function arrayFileFormat($value): array
    {
        $files = self::arrayFormat($value);
        $files_format = [];
        foreach ($files as $row) {
            $files_format[] = self::fileFormat($row);
        }
        return $files_format;
    }

    public static function fileFormat($value)
    {
        $image = "default.jpg";
        if (@$value['options']['image']) {
            $image = $value['options']['image'];
        }
        return FileManager::serveFile(
            'uploads/setting/' . $value['value'], 'assets/notfounds/' . $image
        );
    }

    public function update($request)
    {
        $input = $request->all();

        $ecommerce_enabled = @$request->get('checkbox')['ecommerce_tracking_enabled'] != null;
        if ($ecommerce_enabled) {
            $textarea = $request->get('textarea', []);
            $head_codes = $textarea['head_codes'] ?? Setting::where('key', 'head_codes')->value('value') ?? '';
            $body_codes = $textarea['body_codes'] ?? Setting::where('key', 'body_codes')->value('value') ?? '';
            $gtm_error = GtmSnippetValidator::validate((string) $head_codes, (string) $body_codes);
            if ($gtm_error !== null) {
                return Redirect::back()->with('error', $gtm_error);
            }
        }

        //تم
        if (@$input['theme']){
            $theme = Setting::where('key', 'theme')->first();
            if ($theme['value'] != $input['theme']){
                $theme->update([
                    'value' => @$input['theme'],
                ]);
                $banners = Banner::orderBy('id','DESC')->get();
                foreach ($banners as $banner){
                    $banner->update([
                        'show_in_first_page' => false
                    ]);
                }
                Banner::create([
                    'show_in_first_page' => true,
                    'title' => 'First Banner',
                    'image' => null,
                    'image_mobile' => null
                ]);

                Banner::create([
                    'show_in_first_page' => true,
                    'title' => 'Second Banner',
                    'image' => null,
                    'image_mobile' => null
                ]);
            }

        }
        //موارد تکست یا آرایه هایی که نیاز به تغییر ندارند
        $arrays = $request->get('array', []);

        $text = $request->get('text', []);
        $ckeditor = $request->get('ckeditor', []);
        $textarea = $request->get('textarea', []);
        $select = $request->get('select', []);
        $combinedArray = array_merge($arrays, $text, $ckeditor, $textarea, $select);
        foreach ($combinedArray as $key2 => $array) {

            $x = Setting::where('key', $key2)->first();
            if ($x){
                $x->update([
                    'value' => strlen(NumberHelper::persian2LatinDigit($array)) != 0 ? NumberHelper::persian2LatinDigit($array)  : null
                ]);
            }

        }

        //تنظیمات منو
        $menu_data = $request->get('menu_data') ? $request->get('menu_data') : [];
        $menu_data_check = $request->get('menu_data') ? json_decode($request->get('menu_data'), true) : [];
        $menu_count_check = MenuHelper::checkCount($menu_data_check);
        if ($menu_count_check === false){
            return Redirect::back()->with('error', 'نمیتوان بیش از ۷ آیتم در منو قرار داد');

        }
        $types = ['product' => ' منوی نوع محصول بیش از یکبار تکرار شده است', 'service' => ' منوی نوع خدمات بیش از یکبار تکرار شده است'];
        foreach ($types as $type => $errorMessage) {
            $count = count(array_filter($menu_data_check, function ($item) use ($type) {
                return $item['type'] === $type;
            }));
            if ($count > 1) {
                return Redirect::back()->with('error', $errorMessage);
            }
        }
        Setting::firstOrCreate(['type' => "menu"])->update(
            [
                'value' => strlen(NumberHelper::persian2LatinDigit($menu_data)) != 0 ? NumberHelper::persian2LatinDigit($menu_data)  : null
            ]
        );
        //تنظیمات فوتر
        $footer_data = $request->get('footer_data') ? $request->get('footer_data') : [];
        Setting::firstOrCreate(['type' => "footer"])->update(
            [
                'value' => strlen(NumberHelper::persian2LatinDigit($footer_data)) != 0 ? NumberHelper::persian2LatinDigit($footer_data)  : null
            ]
        );

        // ساعت کاری سالن
        $work_hours = $request->get('work_hours');
        $data_array = [];
        if ($work_hours){

            foreach ($work_hours as $key => $value) {
                $from = $work_hours[$key]['from'];
                $to = $work_hours[$key]['to'];
                if (($from < 0 || $from > 24) || ($to < 0 || $to > 24)) {
                    return Redirect::back()->with('error', ' ساعت کاری وارد شده نا معتبر است');
                }
                if ($from > $to) {
                    return Redirect::back()->with('error', ' مقدار "از" بزرگتر از مقدار "تا" است');
                }
                $data_array[$key] = [
                    'from' => $from,
                    'to' => $to,
                ];
            }
            Setting::where('key', 'work_hours')->first()->update(
                [
                    'value' => $data_array
                ]
            );
        }

//چک باکس ها
        $checkboxes = Setting::where('type', 'checkbox')->get();
        foreach ($checkboxes as $checkbox) {
            $checkbox->update([
                'value' => @$request->get('checkbox')[$checkbox['key']] != null ? 1 : 0
            ]);
        }



        //فایل هایی که بصورت مولتیپل اضافه میشن
        if (@$input['array_files']) {
            foreach ($input['array_files'] as $key4 => $array_files) {
                $fileNames = [];
                foreach ($array_files as $key5 => $array_file) {
                    if (!TestImage::is_image($array_file)) {
                        return Redirect::back()->with('error', ' تصویر وارد شده نا معتبر است');
                    }
                    $fileNameArray = FileManager::upload($array_file, "setting");
                    $fileNames[] = $fileNameArray;
                }
                Setting::where('key', $key4)->first()->update([
                    'value' => implode(',', $fileNames),
                ]);
            }
        }

        //فایل هایی که بصورت تکی اضافه میشن
        if (@$input['input_file']) {
            foreach ($input['input_file'] as $key3 => $input_file) {

                if (!TestImage::is_image($input_file)) {
                    return Redirect::back()->with('error', ' تصویر وارد شده نا معتبر است');
                }
                $maxSize = 200 * 1024;
                if ($key3 === 'logo' && $input_file->getSize() > $maxSize) {
                    return Redirect::back()->with('error',
                        'حجم فایل لوگو نباید از ۲۰۰ کیلوبایت بیشتر باشد.');
                }

                // Favicon must stay PNG (not WebP) for browser tabs and Google search results
                $fileName = $key3 === 'favicon'
                    ? FileManager::uploadRaw($input_file, "setting")
                    : FileManager::upload($input_file, "setting", null, 90);
                Setting::where('key', $key3)->first()
                    ->update([
                        'value' => $fileName
                    ]);
            }
        }

        //فایل هایی که برای درباره ما اضافه میشن
        if (@$input['input_file_about']) {
            foreach ($input['input_file_about'] as $key4 => $input_file_about) {
                if (!TestImage::is_image($input_file_about)) {
                    return Redirect::back()->with('error', ' تصویر وارد شده نا معتبر است');
                }
                $fileNameAbout = FileManager::upload($input_file_about, "setting");
                Setting::where('key', $key4)->first()
                    ->update([
                        'value' => $fileNameAbout
                    ]);
            }
        }

        // موقتاً به دلیل اختلال API
//        $notificationValidationResult = $this->validateActiveNotificationsAfterSave($request);
//        if ($notificationValidationResult) {
//            return $notificationValidationResult;
//        }

    }

    private function validateActiveNotificationsAfterSave($request)
    {
        $activeNotifications = (int)(Setting::where('key', 'active_notifications')->value('value') ?? 0);

        if ($activeNotifications !== 1) {
            return null;
        }

        $kavenegarSender = $request->input('text.kavenegar_sender', Setting::where('key', 'kavenegar_sender')->value('value'));
        $testPhone = $request->input('text.kavenegar_number_test', Setting::where('key', 'kavenegar_number_test')->value('value'));

        if (empty($kavenegarSender) || trim((string)$kavenegarSender) === '' || empty($testPhone) || trim((string)$testPhone) === '') {
            $this->updateActiveNotificationsStatus(0);
            return Redirect::back()
                ->withInput()
                ->with('error', 'برای فعال‌سازی «درخواست‌های موجود/حراج شد خبرم کن»، باید هم شماره خط خدماتی و هم شماره تست کاوه‌نگار را وارد کنید.');
        }

        $cleanSender = preg_replace('/[\s\-\(\)]/', '', (string)$kavenegarSender);
        $isValid = $this->ValidateKavenegarSernder($cleanSender);

        if (!$isValid['valid']) {
            $this->updateActiveNotificationsStatus(0);
            return Redirect::back()->withInput()->with('error', $isValid['message']);
        }

        return null;
    }

    private function updateActiveNotificationsStatus(int $value): void
    {
        Setting::where('key', 'active_notifications')->update([
            'value' => $value
        ]);
    }

    /**
     * تست شماره ارسال‌کننده کاوه‌نگار
     */
    private function ValidateKavenegarSernder(?string $cleanSender): array
    {
        if (!$cleanSender) {
            return ['valid'=>false,'message'=>'شماره خط خدماتی خالی است'];
        }

        $kave = Setting::where('key','kavenegar_key')->whereNotNull('value')->value('value');
        $test_phone = Setting::where('key','kavenegar_number_test')->whereNotNull('value')->value('value');

        if (!$kave) {
            Log::warning('Kavenegar key missing during sender validation');
            return ['valid'=>false,'message'=>'کلید کاوه‌نگار تنظیم نشده'];
        }

        if (!$test_phone) {
            return ['valid'=>false,'message'=>'شماره تست کاوه‌نگار تنظیم نشده'];
        }

        $sms = new Sms();

        $result = $sms->sendSmsArrayTest([
            [
                "receptor"=>$test_phone,
                "message"=>"تست اعتبار خط خدماتی"
            ]
        ], $cleanSender);

        if (!$result['success']) {
            $msg = $result['message'] ?? 'خطا در ارتباط با کاوه‌نگار';
            Log::warning("Invalid Kavenegar sender: ".$msg);
            return [
                'valid'=>false,
                'message'=>"خط خدماتی معتبر نیست: ".$msg
            ];
        }
        return ['valid'=>true];
    }
}
