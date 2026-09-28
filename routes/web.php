<?php

use App\Http\Controllers\ApiCallController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FirstPageController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\LegacyImportController;
use App\Http\Controllers\LegacySiteRedirectController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Panel\PanelController;
use App\Http\Controllers\SampleController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\Shop\AddressController;
use App\Http\Controllers\Shop\BasketController;
use App\Http\Controllers\Shop\BrandController;
use App\Http\Controllers\Shop\CategoryController;
use App\Http\Controllers\Shop\OrderController as OrderControllerAlias;
use App\Http\Controllers\Panel\AddressController as AddressControllerAlias;
use App\Http\Controllers\Panel\OrderController;
use App\Http\Controllers\Admin\ProductImportController as AdminProductImportController;
use App\Http\Controllers\Admin\VideoController as AdminVideoController;
use App\Http\Controllers\Shop\ProductController;
use App\Http\Controllers\SiteConfigController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\Torob\TorobProductController;
use App\Http\Controllers\Torob\TorobOrderController;
use App\Http\Controllers\UsController;
use App\Library\SiteHelper;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\SitemapController;
use App\Modules\General\Helper\Sms;
use App\Modules\Order\Entities\Bank;
use App\Modules\Order\Library\ZarinPal;
use App\Modules\Order\Services\OrderService;
use App\Modules\Product\Entities\ProductNotification;
use App\Modules\Setting\Entities\Setting;

$theme_provider = app(\App\Modules\General\Helper\ThemeProvider::class);
//Route::get('/check-zarinpal/{id}', function ($id) {
//
//    $my_order = OrderService::byId($id);
//
//    if (!$my_order) {
//        abort(404, 'Order not found.');
//    }
//
//    // اگر وضعیت سفارش در حالت پرداخت نیست، بررسی انجام نشود
////    if (!in_array($my_order->order_status, ['paying'])) {
////        Log::info("Skipping CheckTransactionStatus for order ID {$id} as it has not valid status.");
////        return response()->json(['message' => 'Invalid order status.'], 400);
////    }
//
//    $bank = Bank::findOrFail($my_order->bank_id);
//    $bankConfig = json_decode($bank->config, true);
//    $merchant = $bankConfig['MerchantId'] ?? null;
//
//    $transactionInfo = json_decode($my_order->transaction_info, true);
//    $authority = $transactionInfo['post']['Authority'] ?? null;
//
//    if (!$authority) {
//        return response()->json(['message' => 'Authority not found.'], 400);
//    }
//    $site_name = strtolower(@SiteHelper::getInformation()['site_name']);
//    // اگر site_name در تنظیمات داری، جایگزین کن
//    $myBank = new ZarinPal($bank,$site_name);
//
//    try {
//        $check = $myBank->inquiry($authority);
//        Log::info($check);
//        dd($check);
//        if (($check['status'] ?? null) === 'failed') {
//
//            $my_order->update([
//                'order_status' => 'unpaid'
//            ]);
//
//            return response()->json(['message' => 'Payment failed.']);
//
//        } else {
//
//            $transaction_info = [
//                'post' => [
//                    'Authority' => $authority
//                ],
//                'verify' => [
//                    'RefID' => $check['RefID'] ?? null
//                ]
//            ];
//
//            $my_order->update([
//                'transaction_info' => json_encode($transaction_info),
//                'order_status' => 'paid'
//            ]);
//
//            OrderService::inventory($my_order);
//
//            // پیامک به کاربر
//            if (!empty($my_order->user->mobile)) {
//                (new Sms())->sendLookup('userBuy', [
//                    'token' => $my_order->id,
//                ], $my_order->user->mobile);
//            }
//
//            // پیامک به ادمین
//            if ($admin_mobile = Setting::where('key', 'admin_mobile')->value('value')) {
//                (new Sms())->sendLookup('adminBuy', [
//                    'token' => $my_order->id,
//                ], $admin_mobile);
//            }
//
//            return response()->json(['message' => 'Payment successful.']);
//        }
//
//    } catch (\Exception $e) {
//
//        Log::error($e->getMessage());
//
//        $my_order->update([
//            'order_status' => 'unpaid'
//        ]);
//
//        return response()->json(['message' => 'Exception occurred.'], 500);
//    }
//});

Route::get('/', [FirstPageController::class, $theme_provider->getValue()])->name('index');
Route::get('/index2', [FirstPageController::class, $theme_provider->getValue()]);
Route::get('robots.txt', [UsController::class, 'showRobots'])->name('robots');
Route::controller(SitemapController::class)->group(function () {
    Route::get('sitemap.xml', 'index')->name('sitemap');
    Route::get('sitemap-static-pages.xml', 'staticPages')->name('sitemap-static-pages');
    Route::get('sitemap-services.xml', 'services')->name('sitemap-services');
    Route::get('sitemap-blogs.xml', 'blogs')->name('sitemap-blogs');
    Route::get('sitemap-blog-categories.xml', 'blogCategories')->name('sitemap-blog-categories');
//    Route::get('sitemap-products.xml', 'products')->name('sitemap-products');
    Route::get('sitemap-product-categories.xml', 'productCategories')->name('sitemap-product-categories');
    Route::get('sitemap-tags.xml', 'tags')->name('sitemap-tags');
    Route::get('sitemap-pages.xml', 'pages')->name('sitemap-pages');
    Route::get('sitemap-brands.xml', 'brands')->name('sitemap-brands');
    Route::get('/sitemap-products-{chunk}.xml', 'productChunk')->where('chunk', '[0-9]+')->name('sitemap-products-chunk');
    Route::get('brands-sitemap.xml', 'brands');
    Route::get('blog-categories-sitemap.xml', 'blogCategories');
    Route::get('blog-sitemap.xml', 'blogs');
    Route::get('video-sitemap.xml', 'blogs');
    Route::get('category-sitemap.xml', 'productCategories');
    Route::get('product-sitemap.xml', 'productChunk')->defaults('chunk', 1);

});
Route::middleware('throttle:crawler-endpoints')->controller(ApiCallController::class)->name('api-call.')->group(function () {
    Route::get('/emall-products', 'emallProduct')->name('emall-products');
    Route::get('/zarebin-products', 'zarebinProduct')->name('zarebin-products');
});

//Service
Route::controller(ServiceController::class)->name('service.')->group(function () use ($rout_settings) {
    Route::get('/services', 'index')->name('list');
    Route::get('/' . @$rout_settings['service_prefix'] . '/{url}', 'detail')->name('detail');
    Route::post('/service-request', 'serviceRequest')->name('service-request');
});

Route::controller(\App\Http\Controllers\ConvertController::class)->name('convert.')->group(function () {
    Route::get('/add-field-to-tables', 'addFieldToTables')->name('add-field-to-tables');
    Route::get('/empty-tables', 'truncateTables')->name('empty-tables');
    Route::get('/convert', 'convert')->name('convert');
    Route::get('/redirect-urls', 'redirectUrls')->name('redirect-urls');
    Route::get('/reset-is-convert', 'resetIsConvert')->name('reset-is-convert');
    Route::get('/convert-cmscore-namespaces', 'convertCmsCoreNamespaces')
        ->middleware('throttle:5,1')
        ->name('convert-cmscore-namespaces');
    Route::get('/null-equal-discounted-prices', 'nullEqualDiscountedPrices')
        ->middleware('throttle:5,1')
        ->name('null-equal-discounted-prices');
//    Route::get('/convert-spfs', 'productMainSpecifications');
//    Route::get('/convert-variant', 'productVariant');

});

Route::controller(LegacyImportController::class)->prefix('legacy-import')->name('legacy-import.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/prepare', 'prepare')->name('prepare');
    Route::get('/catalog', 'catalog')->name('catalog');
    Route::get('/content', 'content')->name('content');
    Route::get('/services', 'services')->name('services');
    Route::get('/users', 'users')->name('users');
    Route::get('/settings', 'settings')->name('settings');
    Route::get('/commerce', 'commerce')->name('commerce');
    Route::get('/redirects', 'redirects')->name('redirects');
    Route::get('/media', 'media')->name('media');
});

//Us
Route::controller(UsController::class)->name('us.')->group(function () {
    Route::get('/about-us', 'about')->name('about');
    Route::get('/terms-and-regulations', 'terms')->name('terms');
    Route::get('/contact-us', 'contact')->name('contact');
    Route::post('/contact-us', 'postContact')->name('post-contact');
    //purge-cache
    Route::get('/purge-cache', 'flushCache')->name('purge-cache');
    Route::get('/clear-layout-cache', 'clearLayoutCache')->name('clear-layout-cache');
});

//Comment
Route::post('/comment', [CommentController::class, 'postComment'])->name('post-comment');
Route::post('/reply', [CommentController::class, 'postComment']);

//Blogs
Route::controller(BlogController::class)->name('blog.')->group(function () {
    Route::get('/articles', 'index')->name('category-list');
    Route::get('/article/{url}', 'list')->name('list');
    Route::get('/article/{category}/{url}', 'show')->name('detail');
    Route::get('/videos', 'videos')->name('videos');
    Route::get('/videos/{url}', 'detail')->name('video');
});
Route::redirect('/posts', '/articles', 301);
Route::get('/posts/{url}', function (string $url) {
    return redirect('/article/'.$url, 301);
});
Route::get('/post/{url}', [LegacySiteRedirectController::class, 'blogBySlug']);
Route::get('/articles/list/{id}', [BlogController::class, 'listById'])->whereNumber('id');
Route::get('/articles/{id}', [BlogController::class, 'showById'])->whereNumber('id');
//Pages
Route::controller(PageController::class)->name('page.')->group(function () {
    Route::get('/page/{url}', 'detail')->name('detail');
});

//Samples
Route::controller(SampleController::class)->name('portfolio.')->group(function () {
    Route::get('/portfolios', 'index')->name('list');
    Route::get('/portfolio/{url}', 'detail')->name('detail');
    Route::get('/portfolio-filters', 'getServiceForFilter')->name('portfolio-filters');
//    Route::post('/portfolio-vue', 'getListForVue')->name('portfolio-vue');
});

//Gallery
Route::controller(GalleryController::class)->name('gallery.')->group(function () {
    Route::get('/galleries', 'category')->name('category');
    Route::get('/gallery/{url}', 'list')->name('list');
});

//Package
Route::controller(PackageController::class)->name('package.')->group(function () {
    Route::get('/packages', 'list')->name('list');
    Route::get('/package/{url}', 'detail')->name('detail');
});


//category
Route::controller(CategoryController::class)->name('category.')->group(function () {
    Route::get('/categories', 'list')->name('list');
});
Route::get('/categories/{id}', [LegacySiteRedirectController::class, 'categoryById'])->whereNumber('id');
Route::get('/categories-show/{id}', [LegacySiteRedirectController::class, 'categoryById'])->whereNumber('id');
Route::get('/categories-show/{url}', [CategoryController::class, 'detail'])->name('category.detail');
Route::get('/category/{id}', [CategoryController::class, 'showLegacyParent'])->whereNumber('id')->name('category.legacy');
Route::get('/sub-category/{id}', [CategoryController::class, 'showLegacyChild'])->whereNumber('id')->name('category.legacy-child');
Route::get('/category/{url}', function (string $url) {
    return redirect(\App\Library\SiteUrl::category($url), 301);
});

//brand
Route::controller(BrandController::class)->name('brand.')->group(function () {
    Route::get('/brands', 'list')->name('list');
    Route::get('/brands/{url}', 'detail')->name('detail');
});
Route::get('/brand/{url}', function (string $url) {
    return redirect()->route('brand.detail', ['url' => $url], 301);
});

//product
Route::controller(ProductController::class)->name('product.')->group(function () {
    Route::get('/discounted-list', 'getDiscountedProducts')->name('get-discounted-list');
    Route::get('/products', 'getAllProducts')->name('get-all');
    Route::post('/get-compatible-specs', 'getCompatibleSpecifications')->name('get-compatible');
    Route::post('/find-cheapest-variant', 'findCheapestVariant')->name('find-cheapest-variant');
    Route::post('/product-notification', 'notify')->name('notify');
    Route::post('/product-snapp-check', 'checkSnappPay')->name('snapp-check');
});
Route::redirect('/all-products', '/products', 301);
Route::get('/products/{id}', [LegacySiteRedirectController::class, 'productById'])->whereNumber('id');
Route::get('/product/{id}', [ProductController::class, 'detailById'])->whereNumber('id')->name('product.legacy');
Route::get('/product/{url}', [LegacySiteRedirectController::class, 'productBySlug']);

//tag
Route::controller(TagController::class)->name('tag.')->group(function () {
    Route::get('/tags', 'index')->name('list');
    Route::get('/tag/{url}', 'detail')->name('detail');
});

//search
Route::controller(SearchController::class)->name('search.')->group(function () {
    Route::get('/search', 'detail')->name('detail');
});
//auth
Route::controller(AuthController::class)->prefix('/panell')->name('auth.')->group(function () {
    Route::get('/login', 'index')
        ->middleware('throttle:3,1')
        ->name('index');
    Route::post('/post-login', 'login')->name('login');
    Route::get('/check-user-exists', 'checkUserExisting')->name('check-user-exists');
    Route::post("/register", "login")->name('register');
    Route::get('/mobile-code', 'getCode')->name('mobile-code');
    Route::post("/confirm-code", "postCode")->name('confirm-code');
    Route::get('/logout', 'logout')->name('logout');
    Route::get('/mobile-confirm', 'getCode');
    Route::post('/post-mobile-confirm', 'postCode');
});
Route::get('/login2', [AuthController::class, 'index']);
Route::post('/login2', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'index']);
Route::post('/post-register', [AuthController::class, 'login']);
Route::post('/auth-register', [AuthController::class, 'login']);
Route::get('/post-sms', [AuthController::class, 'getCode']);
Route::get('/panel/{path?}', function (?string $path = null) {
    $target = '/panell'.($path ? '/'.$path : '');
    $query = request()->getQueryString();

    return redirect($query ? $target.'?'.$query : $target, 301);
})->where('path', '.*');
//panel
Route::middleware('panel')->prefix('/panell')->name('panel.')->group(function () {

    // PanelController routes
    Route::controller(PanelController::class)->group(function () {
        Route::get('/', 'dashboard')->name('dashboard');
        Route::get('/profile', 'profile')->name('profile');
        Route::post('/edit-profile', 'editProfile')->name('edit-profile');
        Route::post('/order-upload-images/{id}', 'storeImage')->name('store-images');

    });

    // OrderController routes
    Route::controller(OrderController::class)->group(function () {
        Route::get('/order/list', 'index')->name('orders');
        Route::get('/order/detail/{id}', 'detail')->name('order-detail');
        Route::get('/order-factor/{id}', 'factor')->name('order-factor');
    });
    Route::get('/dashboard', function () {
        return redirect()->route('panel.dashboard');
    });
    Route::get('/orders', function () {
        return redirect()->route('panel.orders');
    });
    Route::get('/order/{id}', function ($id) {
        return redirect()->route('panel.order-detail', ['id' => $id]);
    })->whereNumber('id');

    // AddressController routes
    Route::controller(AddressControllerAlias::class)->group(function () {
        Route::get('/address', 'index')->name('address');
        Route::post('/address-create', 'create')->name('address-create');
        Route::post('/address-update', 'update')->name('address-update');
        Route::get('/address-list', 'userAddresses')->name('address-list'); // vue
        Route::post('/city', 'city')->name('cities'); // vue
        Route::get('/states', 'states')->name('states'); // vue
        Route::post('/address-edit', 'edit')->name('address-edit'); // vue
        Route::post('/address-delete', 'destroy')->name('address-delete'); // vue
    });

});

Route::middleware('web')->middleware('AdminPermission')->prefix('admin/video')->name('admin.video.')->group(function () {
    Route::get('/', [AdminVideoController::class, 'index'])->name('index');
    Route::get('/create', [AdminVideoController::class, 'create'])->name('create');
    Route::post('/create', [AdminVideoController::class, 'store'])->name('create');
    Route::get('/edit/{id}', [AdminVideoController::class, 'edit'])->name('edit');
    Route::post('/edit/{id}', [AdminVideoController::class, 'update'])->name('edit');
    Route::get('/delete/{id}', [AdminVideoController::class, 'destroy'])->name('delete');
});

// ورود گروهی محصولات – فقط در ادمین
Route::middleware('web')->middleware('AdminPermission')->prefix('admin')->name('admin.product.import.')->group(function () {
    Route::get('product-import', [AdminProductImportController::class, 'index'])->name('index');
    Route::post('product-import', [AdminProductImportController::class, 'store'])->name('store');
    Route::get('product-import/sample', [AdminProductImportController::class, 'sample'])->name('sample');
});

//step-one-shop
Route::controller(BasketController::class)->name('basket.')->group(function () {
    Route::post('/cart/add', 'add')->name('add');
    Route::post('/cart/remove', 'removeItem')->name('cart-item-remove');
    Route::match(['GET', 'POST'], '/cart/content', 'cartItems')->name('cart-items');
    Route::get('/checkout', 'cart')->name('cart');
    Route::get('/checkout/cart-delete', 'delete')->name('cart-delete');
    Route::get('/checkout/list-price', 'listPrice')->name('list-price');
    Route::post('/checkout/menu-basket-items/', 'itemCount')->name('count');
    Route::post('/checkout/add-to-basket', 'add');
    Route::post('/checkout/cart-item-remove', 'removeItem');
    Route::get('/checkout/cart-item-list', 'cartItems');
});
Route::get('/checkout/cart', function () {
    return redirect()->route('basket.cart', [], 301);
});
Route::middleware('panel')->prefix('/checkout')->name('basket.')->group(function () {
    //step-two-shop
    Route::controller(AddressController::class)->group(function () {
        Route::get('/shipping', 'list')->name('shipping');
        Route::post('/address-create', 'create')->name('address-create');
        Route::post('/address-update', 'update')->name('address-update');
        Route::get('/address-list', 'userAddresses')->name('address-list'); //vue
        Route::post('/city', 'city')->name('cities'); //vue
        Route::get('/states', 'states')->name('states'); //vue
        Route::post('/address-edit', 'edit')->name('address-edit'); //vue
        Route::post('/shipments', 'shipments')->name('shipments'); //vue
        Route::post('/set-shipments', 'setShipments')->name('set-shipments'); //vue
        Route::get('/address-price', 'addressPrice')->name('address-price'); //vue
    });
    //step-three-shop
    Route::controller(OrderControllerAlias::class)->group(function () {
        Route::get('/payment', 'cart')->name('payment');
        Route::post('/add-discount', 'addDiscount')->name('add-discount');
        Route::get('/delete-discount', 'deleteDiscount')->name('delete-discount'); //vue
        Route::get('/order-price', 'orderPrice')->name('order-price'); //vue
        //create
        Route::post('/create', 'create')->name('order-create');
        Route::any('/finish', 'finishZarinPal')->name('finish.zarin-pal')->withoutMiddleware('panel');
        Route::any('/finish-saman', 'finishSaman')->name('finish.saman-bank')->withoutMiddleware('panel');
        Route::any('/finish-sadad', 'finishSadad')->name('finish.sadad-bank')->withoutMiddleware('panel');
        Route::any('/finish-snapp-pay', 'finishSnappPay')->name('finish.snapp-pay')->withoutMiddleware('panel');
        Route::any('/finish-saderat', 'finishSaderat')->name('finish.saderat')->withoutMiddleware('panel');
        Route::any('/finish-irandargah', 'finishIrDargah')->name('finish.irandargah')->withoutMiddleware('panel');
        Route::any('/finish-parsian', 'finishParsian')->name('finish.parsian')->withoutMiddleware('panel');
        Route::any('/finish-zibal', 'finishZibal')->name('finish.zibal-bank')->withoutMiddleware('panel');
        Route::any('/finish-aqayepardakht', 'finishAqayePardakht')->name('finish.aqayepardakht')->withoutMiddleware('panel');
        Route::any('/finish-digipay', 'finishDigiPay')->name('finish.digipay')->withoutMiddleware('panel');
        //end Order
        Route::get('/order-details-success/{id}', 'success')->name('success');
        Route::get('/order-details-deposit/{id}', 'successDeposit')->name('order-images');
        Route::get('/order-details-failed/', 'failed')->name('failed');
    });

});



////Route::get('/favorites', function () {
//    return view('pages.panel.favorites.index');
//});


//Route::get('/tickets', function () {
//    return view('pages.panel.ticket.list');
//});
//Route::get('/tickets/id', function () {
//    return view('pages.panel.ticket.detail');
//});
//Route::get('/my-packages', function () {
//    return view('pages.panel.package.index');
//});
//Route::get('/my-addresses', function () {
//    return view('pages.panel.addresses.index');
//});
//Route::get('/shop', function () {
//    return view('shop.first-page.index');
//});


Route::get('/set-config-sites', [SiteConfigController::class, "setConfigSitesData"])
    ->name('set-config-data');

Route::get('/api/storeyab/products', [ApiCallController::class, 'storeyab'])
    ->middleware('throttle:crawler-endpoints');

Route::match(['GET', 'POST'], 'torob_api/v3/products', [TorobProductController::class, 'products'])
    ->middleware(['torob.auth', 'throttle:crawler-endpoints'])
    ->name('torob.products');
Route::match(['GET', 'POST'], 'torob_api/v3/products/view/{slug}', [TorobProductController::class, 'viewProduct'])
    ->middleware(['torob.auth', 'throttle:crawler-endpoints'])
    ->name('torob.products.view');

Route::get('/torob/v1/orders', [TorobOrderController::class, 'orders'])
    ->middleware(['torob.auth', 'throttle:crawler-endpoints']);

Route::post('/vue/product-list', [\App\Modules\Product\Http\Controllers\Api\V1\ProductCategoryController::class, 'getProductListVue'])->name('vue.product-list');
Route::post('/vue/filter-product', [\App\Modules\Product\Http\Controllers\Api\V1\ProductCategoryController::class, 'getProductListVue'])->name('vue.filter-product');
Route::any('/finish', [OrderControllerAlias::class, 'finishZarinPal'])->name('site.cart.finish');
Route::post('/post-checkout', [OrderControllerAlias::class, 'create'])->middleware('panel')->name('site.cart.post-checkout');
Route::post('/discount/add', [OrderControllerAlias::class, 'addDiscount'])->middleware('panel');
Route::get('/cache', [UsController::class, 'flushCache']);
Route::get('/cache-clear', [UsController::class, 'flushCache']);

$reservedSiteSegment = implode('|', [
    'admin', 'api', 'panel', 'panell', 'checkout', 'article', 'articles', 'videos',
    'brands', 'brand', 'products', 'product', 'categories', 'category', 'categories-show',
    'cart', 'discount', 'search', 'contact-us', 'about-us', 'terms-and-regulations',
    'services', 'page', 'portfolios', 'portfolio', 'galleries', 'gallery', 'packages',
    'package', 'tags', 'tag', 'posts', 'post', 'login', 'login2', 'register',
    'legacy-import', 'torob_api', 'emall-products', 'zarebin-products', 'set-config-sites',
    'purge-cache', 'clear-layout-cache', 'cache', 'cache-clear', 'comment', 'reply',
    'finish', 'discounted-list', 'all-products', 'post-register', 'post-sms',
    'sub-category', 'index2', 'vue', 'post-checkout',
    'auth-register', 'storage', 'assets', 'uploads', 'vendor', 'build',
]);

Route::get('/{category}/{url}', [ProductController::class, 'detail'])
    ->where('category', '^(?!(?:'.$reservedSiteSegment.')$).+')
    ->where('url', '[^/]+')
    ->name('product.detail');

Route::get('/{url}', [CategoryController::class, 'detail'])
    ->where('url', '^(?!(?:'.$reservedSiteSegment.')$)(?!.*\.(?:xml|txt|css|js|map|ico)$).+')
    ->name('category.listing');
