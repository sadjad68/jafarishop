<?php
namespace App\Modules\Product\Services;

use App\Modules\Product\DTO\SpecificationDTO;
use App\Modules\Product\Entities\Specification;

class SpecificationService
{
    public function create(SpecificationDTO $specificationDTO)
    {
        $specification = Specification::create([
            'title' => $specificationDTO->getTitle(),
            'active' => $specificationDTO->getActive(),
            'is_filter' => $specificationDTO->getIsFilter(),
            'is_color' => $specificationDTO->getIsColor(),
            'type' => $specificationDTO->getType(),
            'parent_id' => $specificationDTO->getParentId(),
        ]);
        $specification->categories()->sync($specificationDTO->getCategories());
    }
    public function update(int $id, SpecificationDTO $specificationDTO)
    {
        $specification = Specification::findOrfail($id);
        $specification->update([
            'title' => $specificationDTO->getTitle(),
            'active' => $specificationDTO->getActive(),
            'is_filter' => $specificationDTO->getIsFilter(),
            'is_color' => $specificationDTO->getIsColor(),
            'type' => $specificationDTO->getType(),
            'parent_id' => $specificationDTO->getParentId(),
        ]);


        $specification->categories()->sync($specificationDTO->getCategories());

    }

    public function deleteOne(int $id): void
    {
        $specification = Specification::findOrFail($id);

        $specification->delete();
    }
    public function deleteRoot(int $id): void
    {
        $specification = Specification::findOrFail($id);
        $specification->product_values()->delete();

        $specification->delete();
    }

    //
    public static function findAll($query = [], $except_id = null)
    {
        $specifications = Specification::query();
        if ($except_id) {
            $specifications->where('id', '<>', $except_id);
        }
        if (isset($query['first_page'])) {
            $specifications->firstPage();
        }
        if (isset($query['list'])) {
            $specifications->whereNull('parent_id');
        }
        if (isset($query['related'])) {
            $specifications->where('parent_id', $query['parent_id']);
        }
        if (isset($query['categories'])) {
            $specifications->whereNull('parent_id')
                ->where(function ($query2) use ($query) {
                    $query2->whereHas('categories', function ($query3) use ($query) {
                        $query3->whereIn("product_category_id", $query['categories']);
                    })->orWhereDoesntHave('categories');
                })
                ->with('children');
        }
        if (isset($query['is_filter'])) {
            $specifications->where('is_filter','1');
        }

        return $specifications->orderby('id', 'DESC')->get();
    }

    public static function getFormatTextSpecifications($product){
        $text_specifications = $product->specification_vals()->orderBy('specification_id')->get();
        $array_format = [];
        foreach($text_specifications as $row){
            $array_format[] = [
                'id' => @$row->id,
                'value' => @$row->value,
                'specification' => @$row->specification->title,
                'specification_id' => @$row->specification_id,
            ];
        }
        return collect($array_format)->groupBy('specification_id');
    }
    public static function getFormatTextSpecificationsActive($product): \Illuminate\Support\Collection
    {
        $text_specifications = $product->specification_active_vals()
            ->orderBy('specification_id')
            ->get();

        $array_format = [];

        foreach ($text_specifications as $row) {
            $array_format[] = [
                'id' => $row->id,
                'value' => $row->value,
                'specification' => $row->specification?->title,
                'specification_id' => $row->specification_id,
            ];
        }

        return collect($array_format)->groupBy('specification_id');
    }


}
