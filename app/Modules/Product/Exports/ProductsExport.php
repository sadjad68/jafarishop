<?php

namespace App\Modules\Product\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Modules\Product\Entities\Product;
use InvalidArgumentException;
use App\Modules\Product\Entities\ProductCategory;

//Todo : use from collection for better performance
//Todo : set limit for results (5000) and show warning
class ProductsExport implements FromCollection
//class ProductsExport implements FromCollection
{

    function __construct($request) {
        $this->request = $request;
    }

    public function collection()
    {
        $request = $this->request;
        $query = Product::query();
        if (@$request['brand_id']) {
            $query->where('brand_id', $request['brand_id']);
        }
        if (@$request['category_id']) {
            $product_category = ProductCategory::findOrFail(@$request['category_id']);
            $children = $product_category->children;

            //products
            $category_ids = [];
            $category_ids[] = $product_category->id;
            foreach ($product_category->children as $row) {
                $category_ids[] = $row['id'];
                if (count($row->children) > 0) {
                    foreach ($row->children as $child) {
                        $category_ids[] = $child['id'];
                    }
                }
            }
            $query->whereHas('categories', function ($query2) use ($category_ids) {
                $query2->whereIn("product_category_id", $category_ids);
            });
        }
        if ($query->OrderBy('id', 'DESC')->count() > 4000){
            throw new InvalidArgumentException('تعداد رکورد انتخابی بیش از ۴۰۰۰ میباشد لطفا با فیلتر انتخاب کنید');

        }
        $products = $query->get();

        $data_array = [];
        foreach ($products as $key => $pro) {
            if(count($pro->variants) > 0){
                foreach($pro->variants as $variant){
                    $data_array[] = [
                        "شناسه محصول" => @$pro->id,
                        "عنوان محصول" => @$pro->title,
                        "شناسه متغییر" => @$variant->id,
                        "عنوان متغییر" => @$variant->specification->title,
                        "موجودی" => @$variant->stock,
                        "قیمت" => @$variant->price,
                        "قیمت بعد از تخفیف" => @$variant->discounted_price,
                    ];
                }
            }else{
                $data_array[] = [
                    "شناسه محصول" => @$pro->id,
                    "عنوان محصول" => @$pro->title,
                    "شناسه متغییر" => "---",
                    "عنوان متغییر" => "---",
                    "موجودی" => @$pro->stock,
                    "قیمت" => @$pro->price,
                    "قیمت بعد از تخفیف" => @$pro->discounted_price,
                ];

            }
        }


        return collect([
            [
                "شناسه محصول" ,
                "عنوان محصول" ,
                "شناسه متغییر",
                "عنوان متغییر",
                "موجودی",
                "قیمت",
                "قیمت بعد از تخفیف",
            ]  ,$data_array]);
    }
}
