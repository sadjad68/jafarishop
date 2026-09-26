<?php

namespace App\Modules\Service\DTO;

use App\Modules\Service\Http\Requests\ServiceRequest;
use Illuminate\Http\UploadedFile;

class ServiceDTO
{
    public string $title;
    public string|null $description;
    public string $url;
    public int|null $parent_id;
    public UploadedFile|null $image;
    public bool $show_in_first_page;
    public bool $show_in_menu;
    public bool $show_in_footer;
    public string|null $description_position;
    public UploadedFile|null $header_image;
    public string|null $phone_number;
    public string|null $short_description;

    public static function fromRequest(ServiceRequest $request)
    {
        $self = new self();
        $self->title = $request->get('title');
        $self->description = $request->get('description');
        $self->url = trim(str_replace(' ', '-',$request->get('url')));
        $self->parent_id = $request->get('parent_id');
        $self->image = $request->file('image');
        $self->show_in_first_page = $request->has('show_in_first_page');
        $self->show_in_menu = $request->has('show_in_menu');
        $self->show_in_footer = $request->has('show_in_footer');
        $self->phone_number = $request->get('phone_number');
        $self->short_description = $request->get('short_description');
        $self->description_position = $request->get('description_position');
        $self->header_image = $request->file('header_image');
        return $self;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescriptionPosition(): ?string
    {
        return $this->description_position;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getParentId(): ?int
    {
        return $this->parent_id;
    }

    public function getImage(): ?UploadedFile
    {
        return $this->image;
    }

    public function isShowInFirstPage(): bool
    {
        return $this->show_in_first_page;
    }

    public function isShowInManu(): bool
    {
        return $this->show_in_menu;
    }

    public function setShowInManu(bool $show_in_menu): void
    {
        $this->show_in_menu = $show_in_menu;
    }
    public function isShowInFooter(): bool
    {
        return $this->show_in_footer;
    }

    public function setShowInFooter(bool $show_in_footer): void
    {
        $this->show_in_footer = $show_in_footer;
    }
    public function setDescriptionPosition(string $description_position): void
    {
        $this->description_position = $description_position;
    }
    public function getShortDescription(): ?string
    {
        return $this->short_description;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function getHeaderImage(): ?UploadedFile
    {
        return $this->header_image;
    }

}
