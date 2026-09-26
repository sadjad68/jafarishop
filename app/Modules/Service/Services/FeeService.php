<?php
namespace App\Modules\Service\Services;

use App\Modules\Service\DTO\EditFeeDTO;
use App\Modules\Service\DTO\FeeDTO;
use App\Modules\Service\Entities\Fee;

class FeeService
{
    protected $model;

    public function __construct(Fee $model)
    {
        $this->model = $model;
    }
    public function create(FeeDTO $feeDTO)
    {

foreach ($feeDTO->getDto() as $fee){
   Fee::create([
        'description' => @$fee['description'],
        'minimum_price' => $fee['minimum_price'],
        'maximum_price' => $fee['maximum_price'],
        'service_id' => @$fee['service_id'],

    ]);
}


    }
    public function update(int $id, EditFeeDTO $feeDTO)
    {
        $fee = Fee::findOrfail($id);
        $fee->update([
            'description' => $feeDTO->getDescription(),
            'minimum_price' => $feeDTO->getMinimumPrice(),
            'maximum_price' => $feeDTO->getMaximumPrice(),
            'service_id' => $feeDTO->getServiceId(),
        ]);

    }
    public function destroy(int $id)
    {
        Fee::destroy($id);
    }
}
