<?php

namespace App\Modules\Certification\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Modules\Certification\DTO\CertificateDTO;
use App\Modules\Certification\Entities\Certificate;
use App\Modules\Certification\Filters\CertificateFilter;
use App\Modules\Certification\Http\Requests\CertificateRequest;
use App\Modules\Certification\Services\CertificateService;
use App\Modules\General\Helper\CacheHelper;

class CertificateController extends Controller
{
    protected $certificateService;
    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }
    public function index(Request $request)
    {
        $query = Certificate::query();
        if ($request->has(['title'])) {
            $filters = [
                'title' => $request->input('title'),
            ];
            $query = app(CertificateFilter::class)->apply($query, $filters);
        }
        $certificate = $query->orderByDesc('id')->paginate(20);
        return view('admin.certification.index', compact('certificate'));

    }

    public function create()
    {
        return view('admin.certification.create');
    }

    public function store(CertificateRequest $request)
    {
        $this->certificateService->create(CertificateDTO::fromRequest($request));
        CacheHelper::clearCache();
        return redirect()->route('admin.certificate.index', ['page' => $request->get('fallback_page',1)])
            ->with('success', 'گواهی جدید اضافه شد.');

    }

    public function edit($id)
    {
        $data = Certificate::findOrfail($id);
        return view('admin.certification.edit', compact('data'));
    }

    public function update(CertificateRequest $request, $id)
    {
        $this->certificateService->update($id, CertificateDTO::fromRequest($request));
        CacheHelper::clearCache();
        return redirect()->route('admin.certificate.index', ['page' => $request->get('fallback_page',1)])
            ->with('success', 'گواهی ویرایش شد.');
    }

    public function destroy($id)
    {
        $this->certificateService->destroy($id);
        CacheHelper::clearCache();
        return Redirect::back()->with('success', 'آیتم موردنظر با موفقیت حذف شد');
    }
}
