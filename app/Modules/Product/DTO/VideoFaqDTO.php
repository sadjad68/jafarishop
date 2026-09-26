<?php

namespace App\Modules\Product\DTO;
use App\Modules\Product\Http\Requests\VideoFaqRequest;

class VideoFaqDTO
{

    public function getVideos(): array|null
    {
        return $this->videos;
    }
    public function getFaqs(): array|null
    {
        return $this->faqs;
    }
    protected string $product_id;
    public function getProductId() : int
    {
        return $this->product_id;
    }
    public static function fromRequest(VideoFaqRequest $request)
    {
        $self = new self();
        $self->faqs = $request->get('faqs');
        $self->videos = $request->get('videos');
        $self->product_id = $request->get('product_id');
        return $self;
    }
}
