<?php

namespace App\Providers;

use App\Library\NotificationFeatureLogger;
use App\Library\SiteHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Modules\Blog\Services\BlogCategoryService;
use App\Modules\Product\Services\ProductCategoryService;
use App\Modules\Seo\Services\CanonicalService;
use App\Modules\Seo\Services\SeoService;
use App\Modules\Service\Services\ServiceManager;
use App\Modules\Setting\Services\BranchService;
use App\Modules\Setting\Services\SettingService;
use App\Modules\Setting\Services\SocialService;
use App\Modules\Setting\Services\ThemeService;

class ViewServiceProvider extends ServiceProvider
{
    public const LAYOUT_CACHE_KEY_PREFIX = 'layout_data_';

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    public const CANONICALS_CACHE_KEY_PREFIX = 'canonicals_';

    public static function clearLayoutCache(?string $site_name = null): void
    {
        $site_name = $site_name ?? @SiteHelper::getInformation()['site_name'];
        if ($site_name) {
            Cache::forget(self::LAYOUT_CACHE_KEY_PREFIX . $site_name);
        }
    }

    public static function clearCanonicalsCache(?string $site_name = null): void
    {
        $site_name = $site_name ?? @SiteHelper::getInformation()['site_name'];
        if ($site_name) {
            Cache::forget(self::CANONICALS_CACHE_KEY_PREFIX . $site_name);
        }
    }

    /**
     * @param mixed $canonicals Collection, array (legacy API shape), or null
     */
    public static function normalizeCanonicals($canonicals): \Illuminate\Support\Collection
    {
        if ($canonicals === null) {
            return collect();
        }
        if (is_array($canonicals) && isset($canonicals['data']['canonicals'])) {
            return collect($canonicals['data']['canonicals']);
        }

        return collect($canonicals);
    }

    /**
     * Bootstrap services.
     */

    public function getSettingData(){
        $settings = SettingService::getFormatSettings();
        $themes = ThemeService::getArrayThemeData();
        $footer_services = ServiceManager::findAll(['footer' => 1 , 'list'=>1,'select'=>['id','title','url','parent_id','sort']],false);
        //services
        $menu_services = ServiceManager::findAll(['menu' => 1,'list'=>1,'select'=>['id','title','url','parent_id','sort']],false);
        ///product-cat
        $menu_product_categories = ProductCategoryService::findAll(['layout' => 1,'list'=>1,'select'=>['id','title','url','parent_id','sort']],false);
        $footer_product_categories = ProductCategoryService::findAll(['layout' => 1,'list'=>1,'select'=>['id','title','url','parent_id','sort']],false)->take(6);

        $branches = BranchService::findAll();
        $socials = SocialService::findAll();
        $social_icon = SocialService::myIcon();
        $first_seo = SeoService::findUrlStatic("");
        ////posts
        $menu_posts = BlogCategoryService::findAll(['menu' => 1,'list'=>1]);

        return compact(
            'socials',
            'footer_services',
            'menu_services',
            'branches',
            'settings',
            'themes',
            'first_seo',
            'menu_posts',
            'menu_product_categories',
            'footer_product_categories',
            'social_icon'
        );
    }

    public function boot(): void
    {
        if (Schema::hasTable('settings')){
            $core_url = env('PUBLIC_BASE_URL') ?? "https://" . @SiteHelper::getInformation()['core_url'] . '/';
            $site_name = @SiteHelper::getInformation()['site_name'];
            if (session()->get('custom_data') == null) {
                session()->put('custom_data', $site_name.strtotime(Carbon::now()));
                session()->save();
            }

            //cache layout (TTL 1 day). Invalidate when settings change so e.g. active_notifications updates.
            $layout_cache_key = self::LAYOUT_CACHE_KEY_PREFIX . $site_name;
            $layout_data_source = 'cache';
            $previous_cache = Cache::get($layout_cache_key);

            if (Cache::has($layout_cache_key)) {
                $layout_data = Cache::get($layout_cache_key);
            } else {
                try {
                    $layout_data = self::getSettingData();
                    $layout_data_source = 'fresh';

                    // جلوگیری از غیرفعال شدن فیچر: اگر دادهٔ تازه active_notifications را ۱ برنگرداند ولی کش قبلی ۱ داشت، همان ۱ را حفظ کن
                    if ($layout_data && is_array($layout_data['settings'] ?? null)) {
                        $current = (int) ($layout_data['settings']['active_notifications'] ?? 0);
                        $previous = $previous_cache && isset($previous_cache['settings']['active_notifications'])
                            ? (int) $previous_cache['settings']['active_notifications']
                            : 0;
                        if ($current !== 1 && $previous === 1) {
                            $layout_data['settings']['active_notifications'] = 1;
                            $layout_data_source = 'preserved';
                            NotificationFeatureLogger::logPreserved($site_name);
                        }
                    }
                } catch (\Exception $err) {
                    NotificationFeatureLogger::logStaleCacheFallback($site_name, $err->getMessage());
                    $layout_data = $previous_cache ?? null;
                    $layout_data_source = 'stale_cache';
                }
                if ($layout_data != null) {
                    Cache::put($layout_cache_key, $layout_data, now()->addDay());
                }
            }

            if ($layout_data && isset($layout_data['branches'])) {
                $settings = $layout_data['settings'] ?? [];
                $active_notifications = (int) ($settings['active_notifications'] ?? 0);
                $status = $active_notifications === 1 ? 'ENABLED' : 'DISABLED';
                NotificationFeatureLogger::logState($status, $layout_data_source, $site_name ?? '', $active_notifications);

                $main_branch = collect($layout_data['branches'])->first(function ($item) {
                    return $item['main'] == 1;
                }) ?? collect($layout_data['branches'])->first(function ($item) {
                    return $item['main'] == 0;
                });
                View::share([
                    'socials' => $layout_data['socials'],
                    'social_icon' => @$layout_data['social_icon'],
                    'footer_services' => $layout_data['footer_services'],
                    'menu_services' => $layout_data['menu_services'],
                    'branches' => $layout_data['branches'],
                    'main_branch' => $main_branch,
                    'settings' => $layout_data['settings'],
                    'themes' => @$layout_data['themes'],
                    'default_seo' => $layout_data['first_seo'],
                    'menu_product_categories' => @$layout_data['menu_product_categories'] ? $layout_data['menu_product_categories'] : null,
                    'footer_product_categories' => @$layout_data['footer_product_categories'] ? $layout_data['footer_product_categories'] : null,
                    'menu_posts' => @$layout_data['menu_posts'],
                ]);
            }

            //seo statics
            try{
                $seo_data = SeoService::findUrlStatic(implode('/', request()->segments()));
            }catch(\Exception $err){
                $seo_data = null;
            }

            //cache canonical
            $canonical_cache_key = self::CANONICALS_CACHE_KEY_PREFIX . $site_name;
            if (Cache::has($canonical_cache_key)) {
                $canonicals = Cache::get($canonical_cache_key);
            } else {
                try {
                    $canonicals = CanonicalService::findAll();
                } catch (\Exception $err) {
                    $canonicals = null;
                }
                if ($canonicals !== null) {
                    Cache::put($canonical_cache_key, $canonicals, now()->addDay());
                }
            }
            $canonical = url()->current();
            $url = trim(str_replace(url('/'), '', \Illuminate\Support\Facades\URL::current()), '/');

            foreach (self::normalizeCanonicals($canonicals) as $row) {
                $rowUrl = trim(is_array($row) ? ($row['url'] ?? '') : $row->url, '/');
                if ($url === $rowUrl || ('/' . $url) === trim(is_array($row) ? ($row['url'] ?? '') : $row->url, '/')) {
                    $canonicalPath = is_array($row) ? ($row['canonical'] ?? '') : $row->canonical;
                    $canonical = url($canonicalPath);
                    break;
                }
            }
            $theme_provider = app(\App\Modules\General\Helper\ThemeProvider::class);

            View::share([
                'seo_data' => $seo_data,
                'canonical' => $canonical,
                'site_name' => strtolower($site_name),
                'core_url' => $core_url,
                'theme_provider'=>$theme_provider
            ]);
        }

    }
}
