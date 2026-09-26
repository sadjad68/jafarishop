<?php

namespace App\Modules\General\Http\Controllers\Api\V1;

use App\Modules\Service\Http\Resources\ServiceCollection;
use App\Modules\Service\Services\ServiceManager;
use App\Modules\Setting\Services\SettingService;
use App\Modules\Tag\Http\Resources\FirstPageTagCollection;
use App\Modules\Tag\Http\Resources\TagCollection;
use App\Modules\Tag\Services\TagService;
use App\Modules\User\Http\Resources\UserCollection;
use App\Modules\User\Services\UserService;
use App\Modules\Banner\Services\BannerService;
use App\Modules\Setting\Services\BranchService;
use App\Modules\Certification\Http\Resources\CertificateCollection;
use App\Modules\Certification\Services\CertificateService;
use App\Modules\Gallery\Http\Resources\GalleryCategoryCollection;
use App\Modules\Gallery\Http\Resources\GalleryCollection;
use App\Modules\Gallery\Services\GalleryCategoryService;
use App\Modules\Gallery\Services\GalleryService;
use App\Modules\Service\Http\Resources\PackageCollection;
use App\Modules\Service\Services\PackageService;
use App\Modules\Page\Services\PageService;
use App\Modules\Service\Http\Resources\FirstPageWorkSampleCollection;
use App\Modules\Service\Services\WorkSampleService;
use App\Modules\Product\Http\Resources\FirstPageProductCollection;
use App\Modules\Product\Http\Resources\ProductCategoryCollection;
use App\Modules\Product\Http\Resources\ProductCollection;
use App\Modules\Product\Services\ProductService;
use App\Modules\Product\Services\ProductCategoryService;
use Illuminate\Http\JsonResponse;

class FirstPageController
{
    public function __construct(
        PageService $pageService,
        ServiceManager $serviceManager,
        BannerService $bannerService,
        WorkSampleService $workSampleService,
        GalleryCategoryService $galleryCategoryService,
        UserService $userService,
        CertificateService $certificateService,
        PackageService $packageService,
        GalleryService $galleryService,
        SettingService $settingService,
        BranchService $branchService,
        ProductService $ProductService,
        ProductCategoryService $productCategoryService,
        TagService $tagService,
    ) {
        $this->pageService = $pageService;
        $this->serviceManager = $serviceManager;
        $this->bannerService = $bannerService;
        $this->workSampleService = $workSampleService;
        $this->galleryCategoryService = $galleryCategoryService;
        $this->userService = $userService;
        $this->certificateService = $certificateService;
        $this->packageService = $packageService;
        $this->galleryService = $galleryService;
        $this->settingService = $settingService;
        $this->branchService = $branchService;
        $this->ProductService = $ProductService;
        $this->productCategoryService = $productCategoryService;
        $this->tagService = $tagService;
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function getFirstPageData(): JsonResponse
    {
        $query = ['first_page' => true];
        $service_manager = $this->serviceManager->findAll($query, false);
        $serviceCollection = new ServiceCollection($service_manager);
        $serviceCollection->setHiddenFields(['children', 'samples', 'parent_url', 'description']);
        $services = $serviceCollection->toArray(request());
        $banners = $this->bannerService->findAll($query);
        $found_samples = $this->workSampleService->findAll($query, 10);
        $sampleCollection = new FirstPageWorkSampleCollection($found_samples);
        $samples = $sampleCollection->toArray(request());
        $found_gallery_categories = $this->galleryCategoryService->findAll($query, 4);
        $galleryCategoryCollection = new GalleryCategoryCollection($found_gallery_categories);
        $gallery_categories = $galleryCategoryCollection->toArray(request());
        $galleries = new GalleryCollection($this->galleryService->findAll($query));
        $team_members = new UserCollection($this->userService->findAll($query));
        $certificates = new CertificateCollection($this->certificateService->findAll($query));
        $found_packages = $this->packageService->findAll($query, 3);
        $packageCollection = new PackageCollection($found_packages);
        $packageCollection->setHiddenFields(['description']);
        $packages = $packageCollection->toArray(request());
        //products
        $found_products = $this->ProductService->findAll($query);
        $productCollection = new FirstPageProductCollection($found_products);
        $productCollection->setHiddenFields(['description','end_timer']);
        $products = $productCollection->toArray(request());

        //cats
        $query_category = ['first_page' => true];
        $found_product_categories = $this->productCategoryService->findAll($query_category,false);
        $productCategoryCollection = new ProductCategoryCollection($found_product_categories);
        $productCategoryCollection->setHiddenFields(['description']);
        $product_categories = $productCategoryCollection->toArray(request());
        //timer
        $query_timer = ['timer' => true];
        $found_timers = $this->ProductService->findAll($query_timer);
        $productTimerCollection = new ProductCollection($found_timers);
        $productTimerCollection->setHiddenFields(['description']);
        $timer_products = $productTimerCollection->toArray(request());
        //tags
        $found_tag = $this->tagService->findAll($query);
        $tagCollection = new FirstPageTagCollection($found_tag);
        $tags = $tagCollection->toArray(request());
        return response()->json([
            'data' => compact(
                'services',
                'banners',
                'samples',
                'gallery_categories',
                'galleries',
                'team_members',
                'certificates',
                'packages',
                'products',
                'product_categories',
                'timer_products',
                'tags'
            ),
            'success' => true,
        ]);
    }
}
