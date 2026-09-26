<?php

namespace App\Modules\Seo\Http\Controllers\Api\V1;

use App\Modules\Seo\DTO\SeoDTO;
use App\Modules\Seo\Entities\SeoMeta;
use App\Modules\Seo\Http\Requests\SeoRequest;
use App\Modules\Seo\Http\Requests\StaticSeoRequest;
use App\Modules\Seo\Services\RedirectService;
use App\Modules\Seo\Services\SeoService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class RedirectController extends Controller
{
    public function __construct(

        RedirectService  $redirectService,
    )
    {
        $this->redirectService = $redirectService;
    }
    public function index()
    {
        $redirects = $this->redirectService->findAll();
        return response()->json([
            'data' => compact(
                'redirects'
            ),
            'success' => true,
        ]);
    }

}
