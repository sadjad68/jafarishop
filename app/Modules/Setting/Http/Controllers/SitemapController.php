<?php

namespace App\Modules\Setting\Http\Controllers;

use Illuminate\Support\Facades\Config;
use App\Modules\General\Helper\CacheHelper;
use App\Modules\Setting\DTO\SitemapDTO;
use App\Modules\Setting\Entities\SettingPartial;
use App\Modules\Setting\Entities\Sitemap;
use App\Modules\Setting\Entities\Slogan;
use App\Modules\Setting\Services\SettingService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Modules\Setting\Services\SitemapService;

class SitemapController extends Controller
{
    protected SitemapService $sitemapService;

    public function __construct(SitemapService $sitemapService)
    {
        $this->sitemapService = $sitemapService;
    }

    /**
     * /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $option_priority = [
            '0.1' => '0.1',
            '0.2' => '0.2',
            '0.3' => '0.3',
            '0.4' => '0.4',
            '0.5' => '0.5',
            '0.6' => '0.6',
            '0.7' => '0.7',
            '0.8' => '0.8',
            '0.9' => '0.9',
            '1' => '1',
        ];
        $option_change_frequency = [
            'daily' => 'daily',
            'weekly' => 'weekly',
            'monthly' => 'monthly',
            'yearly' => 'yearly',

        ];
        $sitemaps = Config::get('sitemap_structure.sitemap');
        return view('admin.setting.sitemap.edit', compact('sitemaps', 'option_change_frequency', 'option_priority'));
    }

    public function update(Request $request)
    {
        $result = $this->sitemapService->update($request);
        return redirect()->back()->with('success', 'ایتم مورد نظر ویرایش شد.');
    }

}
