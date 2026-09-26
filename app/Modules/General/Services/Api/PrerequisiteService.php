<?php

namespace App\Modules\General\Services\Api;

use App\Modules\Product\Entities\Brand;
use App\Modules\Service\Entities\Service;
use App\Modules\Tag\Entities\Tag;

class PrerequisiteService
{
    public function brands($request)
    {
        $query = Brand::query();
        $page = $request->get("page");

        if ($request->get("title")) {
            $query->where("title", 'like', "%" . $request->get("title") . "%");
        }
        return $query->orderBy("id", "desc")->offset(($page - 1) * 20)->limit(20)->select("id",'title','url')->get();
    }
    public function tags($request)
    {
        $query = Tag::query();
        $page = $request->get("page");

        if ($request->get("title")) {
            $query->where("title", 'like', "%" . $request->get("title") . "%");
        }
        return $query->orderBy("id", "desc")->offset(($page - 1) * 20)->limit(20)->select("id",'title','url')->get();
    }
    public function services($request)
    {
        $query = Service::query();
        $page = $request->get("page");

        if ($request->get("title")) {
            $query->where("title", 'like', "%" . $request->get("title") . "%");
        }
        return $query->orderBy("id", "desc")->offset(($page - 1) * 20)->limit(20)->select("id",'title','url')->get();
    }
}
