<?php

namespace App\Modules\Setting\DTO;

use App\Modules\Setting\Http\Requests\SocialRequest;

class SocialDTO
{
    protected string $icon;
    public function getIcon(): string
    {
        return $this->icon;
    }
    protected string $link;
    public function getLink(): string
    {
        return $this->link;
    }
    protected int $is_app_icon;
    public function getIsAppIcon(): int
    {
        return $this->is_app_icon;
    }
    public static function fromRequest(SocialRequest $request)
    {
        $self = new self();
        $self->icon = $request->get('icon');
        $self->link = $request->get('link');
        $self->is_app_icon = $request->has('is_app_icon') ? 1 : 0;
        return $self;
    }
}
