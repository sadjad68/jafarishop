<?php

namespace App\Providers;

use App\Library\SiteHelper;
use App\Modules\Banner\Providers\BannerServiceProvider;
use App\Modules\Blog\Providers\BlogServiceProvider;
use App\Modules\Certification\Providers\CertificationServiceProvider;
use App\Modules\Comment\Providers\CommentServiceProvider;
use App\Modules\Contact\Providers\ContactServiceProvider;
use App\Modules\Course\Providers\CourseServiceProvider;
use App\Modules\Faq\Providers\FaqServiceProvider;
use App\Modules\Gallery\Providers\GalleryServiceProvider;
use App\Modules\General\Helper\ModuleUtils;
use App\Modules\General\Helper\ThemeProvider;
use App\Modules\General\Providers\GeneralServiceProvider;
use App\Modules\Location\Providers\LocationServiceProvider;
use App\Modules\Order\Providers\OrderServiceProvider;
use App\Modules\Page\Providers\PageServiceProvider;
use App\Modules\Product\Providers\ProductServiceProvider;
use App\Modules\Seo\Providers\SeoServiceProvider;
use App\Modules\Service\Providers\ServicesServiceProvider;
use App\Modules\Setting\Providers\SettingsServiceProvider;
use App\Modules\Tag\Providers\TagServiceProvider;
use App\Modules\User\Providers\UserServiceProvider;
use App\View\Components\CheckBox;
use App\View\Components\CkEditor;
use App\View\Components\ImageInput;
use App\View\Components\Input;
use App\View\Components\MultipleImageInput;
use App\View\Components\MultiSelect;
use App\View\Components\PasswordInput;
use App\View\Components\Select;
use App\View\Components\TextArea;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->configurePublicPath();
        $this->app->register(BannerServiceProvider::class);
        $this->app->register(BlogServiceProvider::class);
        $this->app->register(CertificationServiceProvider::class);
        $this->app->register(CommentServiceProvider::class);
        $this->app->register(ContactServiceProvider::class);
        $this->app->register(CourseServiceProvider::class);
        $this->app->register(FaqServiceProvider::class);
        $this->app->register(GalleryServiceProvider::class);
        $this->app->register(GeneralServiceProvider::class);
        $this->app->register(PageServiceProvider::class);
        $this->app->register(ProductServiceProvider::class);
        $this->app->register(OrderServiceProvider::class);
        $this->app->register(LocationServiceProvider::class);
        $this->app->register(SeoServiceProvider::class);
        $this->app->register(ServicesServiceProvider::class);
        $this->app->register(SettingsServiceProvider::class);
        $this->app->register(TagServiceProvider::class);
        $this->app->register(UserServiceProvider::class);
        $this->app->singleton(ThemeProvider::class, function () {
            return new ThemeProvider();
        });
    }

    public function boot()
    {
        Paginator::useBootstrapFive();

        if (!app()->runningInConsole()) {
            SiteHelper::setSiteInformation();
        }
        Schema::defaultStringLength(191);
        if (env('APP_ENV') == "production") {
            URL::forceScheme('https');
        }
        Blade::directive('file', function (string $file_path) {
            return "<?php echo FileManager::serveFile($file_path); ?>";
        });
        require_once ModuleUtils::app_module_path('General/Helper/file.php');
        require_once ModuleUtils::app_module_path('General/Helper/jdate.php');

        if (env('APP_ENV') == "production" && !app()->runningInConsole()) {
            $site = SiteHelper::getInformation();
            if ($site['template_url'] !== "safaeitire.netgasht.ir" && $site['cms_temporary_domain'] !== 1) {
                URL::forceScheme('https');
            } else {
                URL::forceScheme('http');
                URL::secureAsset('http');
            }
        }

        $this->loadViewComponentsAs('cms', [
            CheckBox::class,
            CkEditor::class,
            ImageInput::class,
            Input::class,
            MultipleImageInput::class,
            MultiSelect::class,
            PasswordInput::class,
            Select::class,
            TextArea::class,
        ]);

        View::composer('admin._layouts.master', function ($view) {
            $orderCount = 0;
            $defaultShippingStatus = \App\Modules\Order\Entities\OrderShippingStatus::query()
                ->where('default', 1)
                ->first();

            if ($defaultShippingStatus) {
                $orderCount = \App\Modules\Order\Entities\Order::query()
                    ->where('shipping_status_id', $defaultShippingStatus->id)
                    ->whereIn('order_status', ['paid', 'wait_for_verification'])
                    ->count();
            }

            $commentCount = \App\Modules\Comment\Entities\Comment::query()
                ->where(function ($query) {
                    $query->where('status', 0)->orWhereNull('status');
                })
                ->count();

            $contactCount = \App\Modules\Contact\Entities\Contact::query()
                ->where(function ($query) {
                    $query->where('status', 0)->orWhereNull('status');
                })
                ->count();

            $serviceRequestCount = \App\Modules\Service\Entities\ServiceRequest::query()
                ->where('is_read', 0)
                ->count();

            $availabilityNotificationCount = \App\Modules\Product\Entities\ProductNotification::query()
                ->where(function ($query) {
                    $query->availability()
                        ->where('is_sent', false);
                })
                ->count();

            $saleNotificationCount = \App\Modules\Product\Entities\ProductNotification::query()
                ->where(function ($query) {
                    $query->discount()
                        ->where('is_sent', false);
                })
                ->count();
            $view->with([
                'order_count' => $orderCount,
                'comment_count' => $commentCount,
                'contact_count' => $contactCount,
                'serviceRequestCount' => $serviceRequestCount,
                'availability_notification_count' => $availabilityNotificationCount,
                'sale_notification_count' => $saleNotificationCount,
                'themeProvider' => app(ThemeProvider::class),
            ]);
        });

        View::composer(['errors::*', 'errors.site-layout', 'errors.admin-layout'], function ($view) {
            $data = $view->getData();

            $errorTheme = 'theme1';
            $errorMainCss = config('themes.theme1.css.main', 'assets/site/css/shared/tpl-site-public.css?v0.84');
            $errorSiteName = config('app.name', 'سایت');
            $errorLogo = null;
            $errorPhone = null;
            $errorFavicon = null;
            $themeColors = [
                'color-one' => '#4f46e5',
                'color-two' => '#7c3aed',
                'color-body' => '#f5f6f8',
                'text-primary' => '#ffffff',
                'text-secondary' => '#252525',
                'bg-table' => '#f9fafb',
            ];

            try {
                $resolvedThemeProvider = $data['theme_provider'] ?? app(ThemeProvider::class);
                $errorTheme = $resolvedThemeProvider->getValue() ?: 'theme1';
                $errorMainCss = $resolvedThemeProvider->getMainCss() ?: $errorMainCss;
            } catch (\Throwable $e) {
            }

            $settings = $data['settings'] ?? (View::shared('settings') ?? null);
            if (is_array($settings)) {
                $errorSiteName = $settings['siteName_fa'] ?? $errorSiteName;
                $errorLogo = $settings['logo'] ?? ($settings['footer_logo'] ?? null);
                $errorPhone = $settings['main_phone_number'] ?? null;
                $errorFavicon = $settings['favicon'] ?? null;
            }

            $themes = $data['themes'] ?? (View::shared('themes') ?? null);
            if (is_array($themes) && isset($themes['color_type'])) {
                $decodedColors = json_decode($themes['color_type'], true);
                if (is_array($decodedColors)) {
                    $themeColors = array_merge($themeColors, $decodedColors);
                }
            }

            $view->with([
                'errorTheme' => $errorTheme,
                'errorMainCss' => $errorMainCss,
                'errorSiteName' => $errorSiteName,
                'errorLogo' => $errorLogo,
                'errorPhone' => $errorPhone,
                'errorFavicon' => $errorFavicon,
                'themeColors' => $themeColors,
            ]);
        });

        Blade::directive('toPersianNumber', function ($expression) {
            return "<?php echo \App\Library\NumberHelper::latin2PersianDigit($expression) ?>";
        });
        Blade::if('mobile', function () {
            if (isset($_SERVER["HTTP_USER_AGENT"])) {
                return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
            } else {
                return false;
            }
        });
    }

    private function configurePublicPath(): void
    {
        $configured = config('setting.public_path');
        if (is_string($configured) && $configured !== '') {
            $path = str_starts_with($configured, DIRECTORY_SEPARATOR)
                ? $configured
                : base_path($configured);
            $this->app->usePublicPath($path);
            return;
        }

        if (is_dir(base_path('public_html'))) {
            $this->app->usePublicPath(base_path('public_html'));
        }
    }
}
