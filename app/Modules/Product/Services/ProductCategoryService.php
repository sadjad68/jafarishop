<?php
namespace App\Modules\Product\Services;

use App\Modules\General\Helper\FileManager;
use App\Modules\General\Helper\FileUploader;
use App\Modules\General\Helper\MakeTree;
use App\Modules\General\Helper\ThemeProvider;
use App\Modules\Product\DTO\ProductCategoryDTO;
use App\Modules\Product\Entities\ProductCategory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use App\Modules\Setting\Entities\Setting;
use App\Modules\Setting\Helper\MenuHelper;

class ProductCategoryService
{
    public function create(ProductCategoryDTO $productCategoryDTO)
    {
        $image = null;
        $check = false;
        $menu_links = Setting::where('key','menu_links')->first();

        foreach (json_decode($menu_links['value'],true) as $value){
            if ($value['type'] == "product"){
                if($value['sidebyside'] == "yes"){
                    $check =true;
                }
            }
        }

        if ($productCategoryDTO->getImage()) {
            if($productCategoryDTO->getImage()->getMimeType() == "image/gif"){
                $image = FileManager::uploadRaw($productCategoryDTO->getImage(), "product-category");
            }else{
                $uploader = new FileUploader($productCategoryDTO->getImage(), "uploads/product-category");
                $uploader->setExtensions(["jpeg", "webp", "png", "jpg"]);
                $uploader->setSizes(["big" => [300, 300],"medium" => [150, 150]]);
                $image = $uploader->upload();
            }

        }
        $active = $productCategoryDTO->getActive();
        $showInSite = $productCategoryDTO->getShowInSite();
        $menu_count = 0;
        $main_menu_count = app(ThemeProvider::class)->getMenuCount();
        if ($productCategoryDTO->getParentId() == null) {
            $menu_count = MenuHelper::checkMenuItems() + 1;
            $active = ($menu_count > $main_menu_count && $check === true) ? 0 : $productCategoryDTO->getActive();
        }

        $category = ProductCategory::create([
            'title' => $productCategoryDTO->getTitle(),
            'description' => $productCategoryDTO->getDescription(),
            'url' => $productCategoryDTO->getUrl(),
            'parent_id' => $productCategoryDTO->getParentId(),
            'image' => $image,
            'active' => $active,
            'show_in_site' => $showInSite,
            'show_in_first_page' => $productCategoryDTO->isShowInFirstPage(),
            'have_price_range' => $productCategoryDTO->getHavePriceRange(),
            'min_price' => $productCategoryDTO->getMinPrice(),
            'max_price' => $productCategoryDTO->getMaxPrice(),
        ]);

        $category->specificationConditions()->sync($productCategoryDTO->getFilterSpecifications());
        // اگر شرط برقرار باشد پیام بازگردانده می‌شود
        return ($productCategoryDTO->getActive() == 1 && $menu_count > $main_menu_count && $productCategoryDTO->getParentId() == null && $check === true)
            ? 'تغییرات اعمال شد و به دلیل تعداد آیتم غیر مجاز در منو، فیلتر نمایش در منو اعمال نشد.'
            : null;

    }
    public function update(int $id, ProductCategoryDTO $productCategoryDTO)
    {
        $check = false;
        $menu_links = Setting::where('key','menu_links')->first();
        foreach (json_decode($menu_links['value'],true) as $value){
          if ($value['type'] == "product"){
            if($value['sidebyside'] == "yes"){
                $check =true;
            }
          }
        }
        $category = ProductCategory::findOrfail($id);
        $image = $category->getRawOriginal('image');
        if ($productCategoryDTO->getImage()) {
            if($productCategoryDTO->getImage()->getMimeType() == "image/gif"){
                $image = FileManager::uploadRaw($productCategoryDTO->getImage(), "product-category");
            }else{
                $uploader = new FileUploader($productCategoryDTO->getImage(), "uploads/product-category");
                $uploader->setExtensions(["jpeg", "webp", "png", "jpg"]);
                $uploader->setSizes(["big" => [300, 300],"medium" => [150, 150]]);
                $image = $uploader->upload();
            }

        }
        $active = $productCategoryDTO->getActive();
        $showInSite = $productCategoryDTO->getShowInSite();
        $menu_count = 0;
        $main_menu_count = app(ThemeProvider::class)->getMenuCount();
        if ($productCategoryDTO->getParentId() == null) {
            if ($active != $category['active']){
                $menu_count = MenuHelper::checkMenuItems() + 1;
                $active = ($menu_count > $main_menu_count && $check === true) ? 0 : $productCategoryDTO->getActive();
            }

         }

        $category->update([
            'title' => $productCategoryDTO->getTitle(),
            'description' => $productCategoryDTO->getDescription(),
            'url' => $productCategoryDTO->getUrl(),
            'parent_id' => $productCategoryDTO->getParentId(),
            'image' => $image,
             'active' => $active,
            'show_in_site' => $showInSite,
            'show_in_first_page' => $productCategoryDTO->isShowInFirstPage(),
            'have_price_range' => $productCategoryDTO->getHavePriceRange(),
            'min_price' => $productCategoryDTO->getMinPrice(),
            'max_price' => $productCategoryDTO->getMaxPrice(),
        ]);

        $category->specificationConditions()->sync($productCategoryDTO->getFilterSpecifications());
        // اگر شرط برقرار باشد پیام بازگردانده می‌شود
        return ($productCategoryDTO->getActive() == 1 && $active == 0 && $productCategoryDTO->getParentId() == null && $check === true)
            ? 'تغییرات اعمال شد و به دلیل تعداد آیتم غیر مجاز در منو، فیلتر نمایش در منو اعمال نشد.'
            : null;

    }
    public function getProductCategoryIdsRecursive($productCategory, &$productCategories, $addToProductCategories = true)
    {
        if ($addToProductCategories) {
            $productCategories[] = $productCategory->id;
        }
        if ($productCategory->children->isNotEmpty()) {
            foreach ($productCategory->children as $child) {
                $this->getProductCategoryIdsRecursive($child, $productCategories);
            }
        }
    }

    public function deleteOne(int $id): void
    {
        $productCategory = ProductCategory::findOrFail($id);

        //delete image
        if ($productCategory->image) {
            FileManager::delete("product-category/" . $productCategory->getRawOriginal('image'));
        }

        //update children
        $productCategories = [];
        $this->getProductCategoryIdsRecursive($productCategory, $productCategories, false);
        $changes = ProductCategory::whereIn('id', $productCategories)->get();
        foreach ($changes as $change) {
            $change->update([
                'parent_id' => null,
            ]);
        }

        //delete itself
        $productCategory->delete();
    }

    public function deleteRoot(int $id)
    {
        $productCategory = ProductCategory::findOrFail($id);

        //delete image
        if ($productCategory->image) {
            FileManager::delete("product-category/" . $productCategory->getRawOriginal('image'));
        }

        //delete children
        $productCategories = [];
        $this->getProductCategoryIdsRecursive($productCategory, $productCategories, false);
        $children = ProductCategory::whereIn('id', $productCategories)->get();
        foreach ($children as $child) {
            if ($child->image) {
                FileManager::delete("product-category/" . $child->getRawOriginal('image'));
            }
            $child->delete();
        }

        //delete itself
        $productCategory->delete();
    }
    public static function findAll($query = [], $format = true, $except_id = null, $limit = null)
    {
//        $product_categories = ProductCategory::query()->with(['children','childrenInMenu']);
        $product_categories = ProductCategory::query()
            ->with([
                'children' => function($q) {
                    $q->select('id','title','url','parent_id','sort','image')->where('show_in_site', 1);
                },
                'childrenInMenu' => function($q) {
                    $q->select('id','title','url','parent_id','sort','image')->where('show_in_site', 1);
                }
            ]);

        if ($except_id) {
            $product_categories->where('id', '<>', $except_id);
        }
        if (isset($query['first_page'])) {
            $product_categories->firstPage();
        }
        if (isset($query['layout'])) {
            $product_categories->where('active',1);
            $product_categories->where('show_in_site',1);
        }
        if (isset($query['list'])) {
            $product_categories->whereNull('parent_id');
        }
        if (isset($query['related'])) {
            $product_categories->where('parent_id',$query['parent_id']);
        }
        if (isset($query['parent_id'])) {
            $product_categories->where('parent_id',$query['parent_id']);
        }
        if (isset($query['filter_categories'])) {
            $product_categories->whereIn('id',$query['filter_categories']);
        }
        if (isset($query['has_products'])) {
            if (isset($query['category_brand'])){
                $product_categories->whereHas('products', function ($query2) use ($query) {
                    $query2->where("brand_id", $query['category_brand']);
                });
            }else{
                $product_categories->whereHas('products');
            }

        }
        if (isset($query['select'])) {

            $product_categories->select($query['select']);
        }
        if ($limit != null) {
            $products = $product_categories->orderby('sort', 'ASC')->take($limit)->get();
        }
        else{
            $products = $product_categories->orderby('sort', 'ASC')->get();

        }
        if ($format) {
            return self::formatProductCategories($products);
        } else {
            return $products;
        }

    }
    //
    public static function findAllSiteMap($query = [], $format = true, $except_id = null, $limit = null)
    {
//        $product_categories = ProductCategory::query()->with(['children','childrenInMenu']);
        $product_categories = ProductCategory::query()
            ->with([
                'children' => function($q) {
                    $q->select('id','title','url','parent_id','sort','image')->where('show_in_site', 1);
                },
                'childrenInMenu' => function($q) {
                    $q->select('id','title','url','parent_id','sort','image')->where('show_in_site', 1);
                }
            ]);
        if (isset($query['select'])) {

            $product_categories->select($query['select']);
        }

            $products = $product_categories->orderby('sort', 'ASC')->get();
        return $products;

    }
    public static function findOne($url)
    {
        return ProductCategory::where('url', $url)
            ->where('show_in_site', 1)
            ->firstOrFail();
    }

    public static function formatProductCategories($product_categories, $paginate = 100000)
    {
        if (!empty($product_categories) && count($product_categories) > 0) {
            MakeTree::getData($product_categories);
            $product_categories = MakeTree::GenerateArray(array('paginate' => $paginate));
        }
        return $product_categories;
    }
    public static function getAllCategoryIdsRecursive($category)
    {
        $ids = [$category->id];

        foreach ($category->children as $child) {
            $ids = array_merge($ids, self::getAllCategoryIdsRecursive($child));
        }

        return $ids;
    }
}
