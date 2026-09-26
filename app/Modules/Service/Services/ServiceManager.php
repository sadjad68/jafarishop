<?php

namespace App\Modules\Service\Services;

use App\Modules\General\Helper\FileManager;
use App\Modules\General\Helper\FileUploader;
use App\Modules\General\Helper\MakeTree;
use App\Modules\General\Helper\ThemeProvider;
use App\Modules\Service\DTO\ServiceDTO;
use App\Modules\Service\Entities\Service;
use App\Modules\Setting\Entities\Setting;
use App\Modules\Setting\Helper\MenuHelper;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ServiceManager
{
    protected $serviceEntity;

    public function __construct(Service $serviceEntity)
    {
        $this->serviceEntity = $serviceEntity;
    }
    public static function findAll($query = [], $format = true, $except_id = null)
    {
        $services = Service::query();
        if ($except_id) {
            $services->where('id', '<>', $except_id);
        }
        if (isset($query['first_page'])) {
            $services->firstPage();
        }
        if (isset($query['menu'])) {
            $services->where('show_in_menu',1);
            $services->with('childrenInMenu');
        }
        if (isset($query['footer'])) {
            $services->where('show_in_footer',1);
        }
//        if (isset($query['show_in_site'])) {
//            $services->where('show_in_site', 1);
//        }
        if (isset($query['list'])) {
            $services->whereNull('parent_id');
        }
        if (isset($query['related'])) {
            $services->where('parent_id',$query['parent_id']);
        }
        if (isset($query['parent_id'])) {
            $services->where('parent_id',$query['parent_id']);
        }
        if (isset($query['blog'])) {
            $services->whereHas('blogs', function ($query2) use ($query) {
                $query2->where("blog_id", $query['specific_id']);
            });
        }
        if (isset($query['select'])) {
            $services->select($query['select']);
        }
        $services = $services->orderby('sort', 'ASC')->get();
        if ($format) {
            return self::formatServices($services->toArray());
        } else {
            return $services;
        }
    }
    public static function findOne($url)
    {
        return Service::where('url',$url)->firstOrFail();
    }

    public static function formatServices($services, $paginate = null)
    {
        if (!empty($services) && count($services) > 0) {
            MakeTree::getData($services);
            $services = MakeTree::GenerateArray(array('paginate' => $paginate));
        }
        return $services;
    }

    public function create(ServiceDTO $serviceDTO)
    {
        $image = null;
        $check = false;
        $menu_links = Setting::where('key','menu_links')->first();
        foreach (json_decode($menu_links['value'],true) as $value){
            if ($value['type'] == "service"){
                if($value['sidebyside'] == "yes"){
                    $check =true;
                }
            }
        }
        if ($serviceDTO->getImage()) {
            $uploader = new FileUploader($serviceDTO->getImage(), "uploads/service");
            $uploader->setExtensions(["jpeg", "webp", "png", "jpg"]);
            $uploader->setSizes(["big" => [1200, 515]]);
            $image = $uploader->upload();
        }

        $header_image = $serviceDTO->getHeaderImage() ? FileManager::upload($serviceDTO->getHeaderImage(), "service") : null;
        $show_in_menu = $serviceDTO->isShowInManu();
        $menu_count = 0;
        $main_menu_count = app(ThemeProvider::class)->getMenuCount();
        if ($serviceDTO->getParentId() == null) {
            $menu_count = MenuHelper::checkMenuItems() + 1;
            $show_in_menu =($menu_count > $main_menu_count && $check === true) ? 0 : $serviceDTO->isShowInManu();

        }
        Service::create([
            'title' => $serviceDTO->getTitle(),
            'description' => $serviceDTO->getDescription(),
            'url' => $serviceDTO->getUrl(),
            'parent_id' => $serviceDTO->getParentId(),
            'image' => $image,
            'show_in_first_page' => $serviceDTO->isShowInFirstPage(),
            'show_in_menu' => $show_in_menu,
            'show_in_footer' => $serviceDTO->isShowInFooter(),
            'short_description' => $serviceDTO->getShortDescription(),
            'phone_number' => $serviceDTO->getPhoneNumber(),
            'description_position' => $serviceDTO->getDescriptionPosition(),
            'header_image' => $header_image,
        ]);
        // اگر شرط برقرار باشد پیام بازگردانده می‌شود
        return ($serviceDTO->isShowInManu() == 1 && $menu_count > $main_menu_count && $serviceDTO->getParentId() == null && $check === true)
            ? 'تغییرات اعمال شد و به دلیل تعداد آیتم غیر مجاز در منو، فیلتر نمایش در منو اعمال نشد.'
            : null;
    }

    public function update(int $id, ServiceDTO $serviceDTO)
    {
        $check = false;
        $menu_links = Setting::where('key','menu_links')->first();
        foreach (json_decode($menu_links['value'],true) as $value){
            if ($value['type'] == "service"){
                if($value['sidebyside'] == "yes"){
                    $check =true;
                }
            }
        }
        $service = Service::findOrFail($id);
        $image = $service->getRawOriginal('image');
        if ($serviceDTO->getImage()) {
            $uploader = new FileUploader($serviceDTO->getImage(), "uploads/service");
            $uploader->setExtensions(["jpeg", "webp", "png", "jpg"]);
            $uploader->setSizes(["big" => [1200, 515]]);
            $image = $uploader->upload();
        }

        $header_image = $serviceDTO->getHeaderImage() ?
            FileManager::upload($serviceDTO->getHeaderImage(), "service")
            : $service->getRawOriginal('header_image');
        $show_in_menu = $serviceDTO->isShowInManu();
        $menu_count = 0;
        $main_menu_count = app(ThemeProvider::class)->getMenuCount();

        if ($serviceDTO->getParentId() == null) {
            if ($show_in_menu != $service['show_in_menu']){
                $menu_count = MenuHelper::checkMenuItems() + 1;
                $show_in_menu = ($menu_count > $main_menu_count && $check === true) ? 0 : $serviceDTO->isShowInManu();
            }

        }

        $service->update([
            'title' => $serviceDTO->getTitle(),
            'description' => $serviceDTO->getDescription(),
            'url' => $serviceDTO->getUrl(),
            'parent_id' => $serviceDTO->getParentId(),
            'image' => $image,
            'show_in_first_page' => $serviceDTO->isShowInFirstPage(),
            'show_in_menu' => $show_in_menu,
            'show_in_footer' => $serviceDTO->isShowInFooter(),
            'short_description' => $serviceDTO->getShortDescription(),
            'phone_number' => $serviceDTO->getPhoneNumber(),
            'description_position' => $serviceDTO->getDescriptionPosition(),
            'header_image' => $header_image,
        ]);
        // اگر شرط برقرار باشد پیام بازگردانده می‌شود
        return ($serviceDTO->isShowInManu() == 1 && $show_in_menu == 0 && $serviceDTO->getParentId() == null && $check === true)
            ? 'تغییرات اعمال شد و به دلیل تعداد آیتم غیر مجاز در منو، فیلتر نمایش در منو اعمال نشد.'
            : null;
    }

    public function getServiceIdsRecursive($service, &$services, $addToServices = true)
    {
        if ($addToServices) {
            $services[] = $service->id;
        }
        if ($service->children->isNotEmpty()) {
            foreach ($service->children as $child) {
                $this->getServiceIdsRecursive($child, $services);
            }
        }
    }

    public function deleteOne(int $id): void
    {
        $service = Service::findOrFail($id);

        //delete image
        if ($service->image) {
            FileManager::delete("service/small/" . $service->getRawOriginal('image'));
            FileManager::delete("service/big/" . $service->getRawOriginal('image'));
        }

        if ($service->header_image) {
            FileManager::delete("service/" . $service->getRawOriginal('header_image'));
        }

        //update children
        $services = [];
        $this->getServiceIdsRecursive($service, $services, false);
        $changes = Service::whereIn('id', $services)->get();
        foreach ($changes as $change) {
            $change->update([
                'parent_id' => null,
            ]);
        }
        //delete itself
        $service->delete();
    }

    public function deleteRoot(int $id)
    {
        $service = Service::findOrFail($id);

        //delete image
        if ($service->image) {
            FileManager::delete("service/small/" . $service->getRawOriginal('image'));
            FileManager::delete("service/big/" . $service->getRawOriginal('image'));
        }
        if ($service->header_image) {
            FileManager::delete("service/" . $service->getRawOriginal('header_image'));
        }

        //delete children
        $services = [];
        $this->getServiceIdsRecursive($service, $services, false);
        $children = Service::whereIn('id', $services)->get();
        foreach ($children as $child) {
            if ($child->image) {
                FileManager::delete("service/small/" . $child->getRawOriginal('image'));
                FileManager::delete("service/big/" . $child->getRawOriginal('image'));
            }
            if ($child->header_image) {
                FileManager::delete("service/" . $child->getRawOriginal('header_image'));
            }
            $child->delete();
        }
        //delete itself
        $service->delete();
    }

    public function findInFirstPage($paginate = 6)
    {
        return Service::firstPage()->take($paginate)->get();
    }

    public function findParents()
    {
        return Service::orderBy('id', 'DESC')->whereNull('parent_id')->get();
    }

    /**
     * Root services whose descendants have at least one work sample.
     */
    public static function findParentsWithSampleChildren(): Collection
    {
        $serviceIdsWithSamples = DB::table('service_work_sample')->distinct()->pluck('service_id');
        if ($serviceIdsWithSamples->isEmpty()) {
            return collect();
        }

        $all = Service::query()
            ->select(['id', 'parent_id', 'title', 'sort'])
            ->get()
            ->keyBy('id');

        $roots = [];
        foreach ($serviceIdsWithSamples as $id) {
            $current = $all->get($id);
            if (!$current || $current->parent_id === null) {
                continue;
            }
            while ($current && $current->parent_id) {
                $parent = $all->get($current->parent_id);
                if (!$parent) {
                    $current = null;
                    break;
                }
                $current = $parent;
            }
            if ($current) {
                $roots[$current->id] = $current;
            }
        }

        return collect($roots)->sortBy('sort')->values();
    }

    public static function collectTreeIds($serviceOrId): array
    {
        $service = $serviceOrId instanceof Service ? $serviceOrId : Service::find($serviceOrId);
        if (!$service) {
            return [];
        }

        $ids = [];
        app(self::class)->getServiceIdsRecursive($service, $ids, true);

        return $ids;
    }
}
