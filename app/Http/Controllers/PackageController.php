<?php

namespace App\Http\Controllers;

use App\Library\Assistant\Modules\V1\Package;
use Illuminate\Routing\Controller;
use App\Modules\Service\Services\PackageService;

class PackageController extends Controller
{
    public function list()
    {
        $packages = PackageService::findAll();
        return view('pages.package-list.index', compact('packages'));
    }
    public function detail($url)
    {
        $package = PackageService::findOne($url);
        return view('pages.package-detail.index', compact('package'));
    }

}
