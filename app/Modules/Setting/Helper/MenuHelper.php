<?php

namespace App\Modules\Setting\Helper;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use App\Modules\General\Helper\ThemeProvider;
use App\Modules\Product\Services\ProductCategoryService;
use App\Modules\Service\Services\ServiceManager;
use App\Modules\Setting\Entities\Setting;

class MenuHelper
{
    /**
     * Empty Eloquent collections are objects, so PHP empty() is always false.
     */
    public static function hasChildren($items): bool
    {
        return is_countable($items) && count($items) > 0;
    }

    public static function checkCount($menuDataCheck)
    {
        $count = self::findCountables($menuDataCheck);
        $menu_count = app(ThemeProvider::class)->getMenuCount();
        // بررسی مجموع تعداد آیتم‌ها
        if ($count > $menu_count) {
            return false;
        }

        return true;
    }

    public static function findCountables($records) {
        $defaultItems = array_filter($records, fn($item) => $item['type'] === 'default');
        $noSideItems = array_filter($records, fn($item) => isset($item['sidebyside']) && $item['sidebyside'] === 'no');

        $uniqueDefaultItems = array_udiff($defaultItems, $noSideItems, function ($a, $b) {
            return strcmp(json_encode($a), json_encode($b));
        });
        $uniqueDefaultItemsCount = count($uniqueDefaultItems);

        $uniqueNoSideItems = array_udiff($noSideItems, $defaultItems, function ($a, $b) {
            return strcmp(json_encode($a), json_encode($b));
        });
        $uniqueNoSideItemsCount = count($uniqueNoSideItems);

        $repeatedItems = array_uintersect($defaultItems, $noSideItems, function ($a, $b) {
            return strcmp(json_encode($a), json_encode($b));
        });
        $repeatedCount = count($repeatedItems);

        $categoriesCount = 0;
        $servicesCount = 0;

        // بررسی آیتم‌های sidebyside با مقدار yes
        $sideBySideYes = array_filter($records, fn($item) => isset($item['sidebyside']) && $item['sidebyside'] === 'yes');
        foreach ($sideBySideYes as $row) {
            if ($row['type'] === 'product') {
                $categoriesCount = count(ProductCategoryService::findAll(['layout' => 1, 'list' => 1], false));
            } elseif ($row['type'] === 'service') {
                $servicesCount = count(ServiceManager::findAll(['menu' => 1, 'list' => 1], false));
            }
        }
        return $repeatedCount + $uniqueDefaultItemsCount + $uniqueNoSideItemsCount + $categoriesCount + $servicesCount;
    }
    public static function checkMenuItems(){

        $record = Setting::where('key', 'menu_links')->first();
        $records = json_decode($record->value, true);

       return self::findCountables($records);


    }
}
