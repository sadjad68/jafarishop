<?php

namespace App\Modules\Seo\Http\Controllers\Api\V1;

use App\Modules\Seo\DTO\SeoDTO;
use App\Modules\Seo\Entities\SeoMeta;
use App\Modules\Seo\Http\Requests\SeoRequest;
use App\Modules\Seo\Http\Requests\StaticSeoRequest;
use App\Modules\Seo\Services\SeoService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SeoController extends Controller
{
    public function __construct(

        SeoService  $seoService,
    )
    {
        $this->seoService = $seoService;
    }
    public function index($url)
    {
        $seo_statics = $this->seoService->findUrlStatic($url);
        return response()->json([
            'data' => compact(
                'seo_statics'
            ),
            'success' => true,
        ]);
    }

}
