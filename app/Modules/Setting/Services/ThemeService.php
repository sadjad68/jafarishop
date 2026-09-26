<?php

namespace App\Modules\Setting\Services;

use App\Modules\General\Helper\FileManager;
use App\Modules\Service\Entities\Service;
use App\Modules\Setting\Entities\Setting;
use App\Modules\Setting\Entities\Theme;
use App\Modules\Product\Entities\ProductCategory;
use Illuminate\Support\Facades\Redirect;

class ThemeService
{
    public static function findAll($keys = [])
    {
        $theme = Theme::query();
        if (count($keys) > 0) {
            $theme->whereIn('key', $keys);
        }
        return $theme->get();
    }

    public static  function getArrayThemeData()
    {
        $data = self::findAll();
        $themes = [];
        foreach ($data as $row) {
            $themes[$row->key] = match ($row->type) {
                default => $row->value,
            };
        }
        return $themes;
    }

    public function update($request)
    {
        foreach ($request->except('_token') as $key => $row) {
//            if ($key == "menu_type" && $row == "mega_menu") {
//// دریافت همه سرویس‌هایی که دارای children هستند و همچنین children آن‌ها را بارگذاری کنید
//                $services_count = Service::whereNull('parent_id')
//                    ->whereHas('children', function ($query) {
//                        $query->whereHas('children');
//                    })
//                    ->with('children.children')
//                    ->count();
//                if ($services_count == 0) {
//                    return Redirect::back()->with('error', 'برای استفاده از مگامنو حداقل یکی از خدمات تا حداقل دو لول زیرمجموعه داشته باشند');
//                }
//
//            }
//            if ($key == "menu_type_category" && $row == "mega_menu") {
//// دریافت همه سرویس‌هایی که دارای children هستند و همچنین children آن‌ها را بارگذاری کنید
//                $cat_count = ProductCategory::whereNull('parent_id')
//                    ->whereHas('children', function ($query) {
//                        $query->whereHas('children');
//                    })
//                    ->with('children.children')
//                    ->count();
//                if ($cat_count == 0) {
//                    return Redirect::back()->with('error', 'برای استفاده از مگامنو حداقل یکی از دسته بندی ها تا حداقل دو لول زیرمجموعه داشته باشند');
//                }
//
//            }
            Theme::where('key', $key)->first()
                ->update([
                    'value' => $row
                ]);
        }
    }
}
