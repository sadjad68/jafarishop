<?php

namespace App\Modules\User\Providers;

use App\Modules\General\Helper\ModuleUtils;
use App\Modules\User\Database\Seeders\UserDatabaseSeeder;
use App\Modules\User\Providers\RouteServiceProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use App\Modules\User\Entities\User;
use App\Modules\User\Observers\UserObserver;

class UserServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'User';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'users';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(ModuleUtils::app_module_path("$this->moduleName/Database/Migrations"));
        User::observe(UserObserver::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            ModuleUtils::app_module_path("$this->moduleName/Config/config.php") => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            ModuleUtils::app_module_path("$this->moduleName/Config/config.php"), $this->moduleNameLower
        );
        $this->publishes([
            ModuleUtils::app_module_path("$this->moduleName/Config/site.php") => config_path('site.php'),
        ], 'config');
                $this->mergeConfigFrom(
            ModuleUtils::app_module_path("$this->moduleName/Config/site.php"), 'site'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = ModuleUtils::app_module_path("$this->moduleName/Resources/views");

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $path = ModuleUtils::app_module_path("$this->moduleName/Resources/lang");
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);
        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
            $this->loadJsonTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom($path, $this->moduleNameLower);
            $this->loadJsonTranslationsFrom($path, $this->moduleNameLower);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }
}
