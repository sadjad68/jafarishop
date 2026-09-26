<?php

namespace App\Modules\Service\Http\Controllers;


use App\Modules\Faq\Entities\Faq;
use App\Modules\Service\DTO\FaqDTO;
use App\Modules\Service\Entities\Service;
use App\Modules\Service\Http\Requests\FaqRequest;
use App\Modules\Service\Services\FaqService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;

class FaqController extends Controller
{
    protected $faqService;
    public function __construct(
       FaqService $faqService,
    )
    {
        $this->faqService = $faqService;


    }
    /**
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($id)
    {
        $service = Service::findOrFail($id);
        return view('admin.service.service.faq.create', compact('service'));

    }
    public function faqs(Request $request)
    {
        $faqs = Faq::orderByDesc('id')->where('faqable_id',$request->get('service_id'))
            ->where('faqable_type','App\Modules\Service\Entities\Service')->get();
        return response()->json(["faqs"=>$faqs]);
    }


    public function store(FaqRequest $request)
    {
        if ($request->get('faqs') == null){
            return redirect()->back()
                ->with('error', 'حداقل یک آیتم را پر کنید');
        }
        $this->faqService->create(FaqDTO::fromRequest($request));
        return redirect()->back()
            ->with('success', 'آیتم های جدید اضافه شد.');
    }


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */

    public function destroyFaq($id)
    {
        $this->faqService->deleteFaq($id);
        return response()->json(['success' => 'آیتم مورد نظر با موفقیت حذف شد.'], 200);

    }
    //

}
