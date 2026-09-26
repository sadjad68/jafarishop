<?php

namespace App\Modules\User\DTO;

use App\Modules\General\Helper\NumberHelper;
use App\Modules\User\Http\Requests\AdminRequest;
use Illuminate\Http\UploadedFile;

class UserDTO
{
    public bool $show_in_first_page;

    protected string $full_name;

    public function getFullName(): string
    {
        return $this->full_name;
    }

    protected string $email;

    public function getEmail(): string
    {
        return $this->email;
    }

    protected string $mobile;

    public function getMobile(): string
    {
        return $this->mobile;
    }

    protected string|null $password = null;

    public function getPassword(): string|null
    {
        return $this->password ? bcrypt($this->password) : null;
    }

    protected array $roles = [];

    public function getRoles(): array
    {
        return $this->roles;
    }

    protected array $user_types;

    public function getUserTypes(): array
    {
        return $this->user_types;
    }

    protected array $services = [];

    public function getServices(): array
    {
        return $this->services;
    }

    protected UploadedFile|null $avatar;

    public function getAvatar(): ?UploadedFile
    {
        return $this->avatar;
    }

    public static function fromRequest(AdminRequest $request)
    {
        $self = new self();
        $self->full_name = $request->get('full_name');
        $self->email = $request->get('email');
        $self->mobile = NumberHelper::persian2LatinDigit($request->get('mobile'));
        $self->password = $request->get('password');
        $self->avatar = $request->file('avatar');
        $self->user_types = $request->get('user_types');
        $self->show_in_first_page = $request->has('show_in_first_page');
        if ($request->get('services')) $self->services = $request->get('services');
        if ($request->get('roles')) $self->roles = $request->get('roles');
        return $self;
    }

    public function isShowInFirstPage(): bool
    {
        return $this->show_in_first_page;
    }

    public function setShowInFirstPage(bool $show_in_first_page): void
    {
        $this->show_in_first_page = $show_in_first_page;
    }
}
