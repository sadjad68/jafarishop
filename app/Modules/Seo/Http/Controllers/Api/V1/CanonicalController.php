<?php

namespace App\Modules\Seo\Http\Controllers\Api\V1;

use App\Modules\Seo\DTO\SeoDTO;
use App\Modules\Seo\Entities\SeoMeta;
use App\Modules\Seo\Http\Requests\SeoRequest;
use App\Modules\Seo\Http\Requests\StaticSeoRequest;
use App\Modules\Seo\Services\CanonicalService;
use App\Modules\Seo\Services\RedirectService;
use App\Modules\Seo\Services\SeoService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CanonicalController extends Controller
{
    public function __construct(

        CanonicalService  $canonicalService,
    )
    {
        $this->canonicalService = $canonicalService;
    }
    public function index()
    {
        $canonicals = $this->canonicalService->findAll();
        return response()->json([
            'data' => compact(
                'canonicals'
            ),
            'success' => true,
        ]);
    }

}
