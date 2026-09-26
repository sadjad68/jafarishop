<?php

namespace App\Modules\Product\DTO;
use App\Modules\Service\Http\Requests\WorkSampleRequest;
use App\Modules\Product\Http\Requests\ImageRequest;
use Illuminate\Http\UploadedFile;

class ImageDTO
{


    protected UploadedFile|array $images;

    public function getImage(): UploadedFile|array
    {
        return $this->images;
    }
    protected string $product_id;
    public function getProductId() : int
    {
        return $this->product_id;
    }
       protected string $product_variant_id;
    public function getVarinats() : array|null
    {
        return $this->variants;
    }

    public static function fromRequest(ImageRequest $request)
    {
        $self = new self();
        $self->images = $request->file('image') == null ? [] : $request->file('image');
        $self->product_id = trim($request->get('product_id'));
        $self->variants = $request->get('product_variant_id');

        return $self;
    }
}
