<?php

namespace App\Modules\Product\Services\Api;

use App\Modules\Product\DTO\Api\ProductDTO;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\ProductCategory;
use App\Modules\Product\Entities\Specification;

class SpecificationService
{
    public function list($request)
    {
        $query = Specification::query();
        $page = @request()->get("page");

        $query->whereNull("parent_id");
        $query->where("active",1);
        if ($request->get("title")) {
            $query->where("title", 'like', "%" . $request->get("title") . "%");
        }
        if ($request->get("product_id")) {
            $product = Product::with("categories:id")->find($request->get("product_id"));

            if ($product) {
                $categoryIds = $product->categories->pluck("id")->toArray();

                $query->where(function ($q) use ($categoryIds) {
                    $q->whereExists(function ($subQuery) use ($categoryIds) {
                        $subQuery->select(\DB::raw(1))
                            ->from('category_specification')
                            ->whereColumn('category_specification.specification_id', 'specifications.id')
                            ->whereIn('category_specification.product_category_id', $categoryIds);
                    })
                    ->orWhereNotExists(function ($subQuery) {
                        $subQuery->select(\DB::raw(1))
                            ->from('category_specification')
                            ->whereColumn('category_specification.specification_id', 'specifications.id');
                    });
                });
            }
        }


        $specifications = $query->orderBy("id", "desc")->offset(($page - 1) * 20)->limit(20)->get();
        $list = [];
        foreach ($specifications as $spec) {
            $list[$spec->id] = [
                'title' => $spec->title,
                'type' => $spec->type == 'select' ? "انتخابی" : 'نوشتاری',
            ];
            if($spec->type == 'select'){
                $list[$spec->id]['values'] = [];
                foreach ($spec->children()->select('id','title')->get() as $key => $val) {
                    $list[$spec->id]['values'] = [
                        'id' => $val->id,
                        'title' => $val->title,
                    ];
                }
            }
        }
        return $list;
    }
    public function values($id)
    {
        $specification = Specification::where([
            'parent_id' => null,
            'type' => 'select'
        ])->findOrFail($id);
        $item = [
            'title' => $specification->title,
        ];

        $item['values'] = [];
        foreach ($specification->children()->select('id','title')->get() as $key => $val) {
            $item['values'] = [
                'id' => $val->id,
                'title' => $val->title,
            ];
        }

        return $item;
    }

    public function productShow(int $id, $request)
    {
        $specification = Specification::findOrFail($id);

        $products = $specification->products()
            ->select('products.id','title','price','discounted_price','url')
            ->withPivot('value')
            ->distinct()
            ->paginate($request->get('per_page', 10));

        $products->getCollection()->transform(function ($product) {
            return [
                'title' => $product->title,
                'price' => $product->price,
                'discounted_price' => $product->discounted_price,
                'url' => $product->url,
                'specification_value' => $product->pivot->value,
            ];
        });

        return [
            'data' => $products->items(),
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage(),
            'per_page' => $products->perPage(),
            'total' => $products->total(),
        ];
    }
}
