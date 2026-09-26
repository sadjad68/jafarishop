<?php

namespace App\Modules\General\Helper;

use Illuminate\Support\Facades\Cache;
use App\Modules\Setting\Entities\Setting;
use App\Modules\Setting\Services\SettingService;

//use Illuminate\Support\Facades\Http;

class ThemeProvider
{
    protected $theme;

    public function __construct()
    {
        $settings = Setting::where('key', 'theme')->first();
        $theme_name = $settings ? $settings['value'] : 'theme1';
        $this->theme = config('themes')[$theme_name];
    }

    public function hasSection($section,$section_name)
    {
        if (in_array($section_name,$this->theme[$section] )) {
            return true;
        }
    }
    public function getValue()
    {
        return $this->theme['value'] ?? null;
    }
    public function getMainCss()
    {
        return $this->theme['css']['main'] ?? null;
    }
    public function getSliderSizes()
    {
        return $this->theme['sliderSizes'];
    }
    public function getMenuCount()
    {
        return $this->theme['menuCount'];
    }
}
