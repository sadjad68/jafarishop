<?php

namespace App\Modules\Product\Http\Controllers;


use App\Modules\Product\Entities\Specification;
use App\Modules\Product\Services\SpecificationService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;

class SpecificationValueController extends Controller
{
    protected $specificationService;
    public function __construct(SpecificationService $specificationService,
    )
    {
        $this->specificationService = $specificationService;

    }
    /**
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $specification = Specification::findOrFail($request->get('id'));
        return view('admin.product.specification.value.create', compact('specification'));

    }
    public function values(Request $request)
    {
        $specification = Specification::findOrFail($request->get('id'));
        $values = $specification->children;
        return response()->json(["values" => $values]);
    }
    public function saveValue(Request $request)
    {
        $color_code = $request->get('color_code') ?
            ($request->get('color_code') == "null" ? '#000000'  : $request->get('color_code'))
            : '#000000';
        $spf = Specification::create([
           'title'=>$request->get('title') ,
           'parent_id'=>$request->get('parent_id') ,
           'color_code'=>$color_code,
        ]);
        return response()->json(['id'=>$spf->id], 200);
    }
    public function updateValue(Request $request)
    {

        $specification = Specification::findOrFail($request->get('id'));
        $color_code = $request->get('color_code') ?
            ($request->get('color_code') == "null" ? '#000000'  : $request->get('color_code'))
            : '#000000';
        $specification->update([
            'title'=>$request->get('title'),
            'color_code'=>$color_code,
        ]);

        return response()->json([], 200);
    }



    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $this->specificationService->deleteOne($id);
        return Redirect::back()->with('success', 'آیتم موردنظر با موفقیت حذف شد');
    }

}
