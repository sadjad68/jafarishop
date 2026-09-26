<?php

namespace App\Modules\Order\DTO;

use App\Modules\General\Helper\NumberHelper;
use App\Modules\Order\Http\Requests\OrderShippingStatusRequest;
use App\Modules\Order\Http\Requests\ShippingMethodRequest;


class ShippingMethodDTO
{
    protected string $title;
    public function getTitle(): string
    {
        return $this->title;
    }

    protected  $price;
    public function getPrice()
    {
        return $this->price;
    }
    protected  $price_ceiling = null;
    public function getPriceCeiling()
    {
        return $this->price_ceiling;
    }

    public bool $status;
    public function getStatus(): bool
    {
        return $this->status;
    }
    protected array $cities = [];

    public function getCities(): array
    {
        return $this->cities;
    }
    protected string|null $user_name;
    public function getUserName(): string|null
    {
        return $this->user_name;
    }
    protected string|null $password;
    public function getPassword(): string|null
    {
        return $this->password;
    }
    public bool $freight_balance;
    public function getFreightBalances(): bool
    {
        return $this->freight_balance;
    }
    protected string|null $type;
    public function getType(): string|null
    {
        return $this->type;
    }
    protected string|null $chapar_type ;
    public function getChaparType(): string|null
    {
        return $this->chapar_type;
    }

    protected string|null $description;
    public function getDescription(): string|null
    {
        return $this->description;
    }
    //sender
    protected string $sender_name;
    public function getSenderName(): string
    {
        return $this->sender_name;
    }
    protected string $sender_company;
    public function getSenderCompany(): string
    {
        return $this->sender_company;
    }
    protected string $sender_phone;
    public function getSenderPhone(): string
    {
        return $this->sender_phone;
    }
    protected string $sender_mobile;
    public function getSenderMobile(): string
    {
        return $this->sender_mobile;
    }
    protected string $sender_email;
    public function getSenderEmail(): string
    {
        return $this->sender_email;
    }
    protected string $sender_address;
    public function getSenderAddress(): string
    {
        return $this->sender_address;
    }
    protected string $sender_postal_code;
    public function getSenderPostalCode(): string
    {
        return $this->sender_postal_code;
    }
    protected int $sender_city_id;
    public function getSenderCityId(): int
    {
        return $this->sender_city_id;
    }
    public static function fromRequest(ShippingMethodRequest $request)
    {

        $self = new self();
        $self->title = $request->get('title');
        $self->price = intval(NumberHelper::persian2LatinDigit($request->get('price')));
        $self->price_ceiling = intval(NumberHelper::persian2LatinDigit($request->get('price_ceiling')));
        $self->status = $request->has('status');
        $self->freight_balance = $request->has('freight_balance');
        $self->user_name = @$request->get('user_name');
        $self->password = @$request->get('password');
        $self->type = @$request->get('type');
        $self->chapar_type = @$request->get('chapar_type');
        $self->description = @$request->get('description');
        $self->sender_name = @$request->get('sender_name');
        $self->sender_company = @$request->get('sender_company');
        $self->sender_phone = @$request->get('sender_phone');
        $self->sender_mobile = @$request->get('sender_mobile');
        $self->sender_email = @$request->get('sender_email');
        $self->sender_city_id = @$request->get('sender_city_id');
        $self->sender_address = @$request->get('sender_address');
        $self->sender_postal_code = @$request->get('sender_postal_code');
        if ($request->get('cities')) $self->cities = $request->get('cities');

        return $self;
    }
}
