<?php

namespace App\Modules\Setting\Http\Controllers;

use Illuminate\Support\Facades\Config;
use App\Modules\General\Helper\CacheHelper;
use App\Modules\Setting\Entities\Setting;
use App\Modules\Setting\Entities\SettingPartial;
use App\Modules\Setting\Services\SettingService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;

class SettingController extends Controller
{
    protected SettingService $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $active_tab_key = $request->input('active_tab') ? str_replace('nav-','',$request->input('active_tab')) : 0;
        $setting_types = Config::get('setting_structure.setting_types');
        $partials = Config::get('setting_structure.setting_partials');
        $setting_data = SettingService::getFormatSettings();
//
//        $partials = SettingPartial::orderBy('sort', 'ASC')->whereShow(1)->whereNull('parent_id')->get();
////
//
//        $array_format = [];
//        foreach ($partials as $row) {
//            $item = [
//                'name' => $row->title,
//                'partials' => [],
//                'fields' => []
//            ];
//            $childs = [];
//            foreach ($row->children as $row2) {
//                $item2 = [
//                    'name' => $row2->title,
//                    'fields' => []
//                ];
//                $fields2 = [];
//                foreach ($row2->settings as $row4) {
//                    $fields2[] = [
//                        'key' => $row4->key,
//                        'value' => json_decode($row4->value) ?? $row4->value,
//                        'p_name' => $row4->p_name,
//                        'type' => $row4->type,
//                        'options' => $row4->options
//                    ];
//                }
//                $item2['fields'] = $fields2;
//                $childs[] = $item2;
//            }
//
//            $fields = [];
//            foreach ($row->settings as $row3) {
//                $fields[] = [
//                    'key' => $row3->key,
//                    'value' => json_decode($row3->value) ?? $row3->value,
//                    'p_name' => $row3->p_name,
//                    'type' => $row3->type,
//                    'options' => $row3->options
//                ];
//            }
//
//            $item['partials'] = $childs;
//            $item['fields'] = $fields;
//            $array_format[] = $item;
//        }
//        return response()->json($array_format);
        return view('admin.setting.setting.edit',
            compact('setting_types', 'partials', 'active_tab_key','setting_data'));
    }

    public function update(Request $request)
    {
        $activeTab = $request->input('active_tab');
        $result = $this->settingService->update($request);
        if (!$result instanceof RedirectResponse) {
            CacheHelper::clearCache();
            return \redirect()->route('admin.setting.index', ['active_tab' => $activeTab])
                ->with('success', 'آیتم ویرایش شد.');
        } else {
            return $result;
        }
    }

}
