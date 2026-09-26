<?php

namespace App\Modules\Service\DTO;

use App\Modules\General\Helper\NumberHelper;
use App\Modules\Service\Http\Requests\PackageRequest;
use Illuminate\Http\UploadedFile;

class PackageDTO
{
    protected string $title;
    public function getTitle() : string
    {
       return $this->title;
    }
    protected string|null $description;
    public function getDescription() : string|null
    {
       return $this->description;
    }
    protected string $url;
    public function getUrl() : string
    {
        return $this->url;
    }
    protected UploadedFile|null $image;

    public function getImage(): UploadedFile|null
    {
        return $this->image;
    }
    protected int|null $show_in_first_page;
    public function getShowInFirstPage(): int|null
    {

        return $this->show_in_first_page;
    }
    protected string|null $price;
    public function getPrice(): string|null
    {

        return $this->price;
    }
    protected string|null $discounted_price;
    public function getDiscountedPrice(): string|null
    {

        return $this->discounted_price;
    }
    protected array $services = [];

    public function getServices(): array
    {
        return $this->services;
    }

    public static function fromRequest(PackageRequest $request)
    {
        $self = new self();
        $self->title = $request->get('title');
        $self->description = $request->get('description');
        $self->image = $request->file('image');
        $self->url = trim(str_replace(' ', '-',@$request->get('url')));
        $self->show_in_first_page = @$request->has('show_in_first_page') ? 1 : 0;
        $self->price = intval(NumberHelper::persian2LatinDigit($request->get('price')));
        $self->discounted_price = intval(NumberHelper::persian2LatinDigit($request->get('discounted_price')));
        if ($request->get('services')) $self->services = $request->get('services');
        return $self;
    }

}
