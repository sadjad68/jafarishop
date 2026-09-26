<?php

namespace App\Modules\Certification\DTO;

use App\Modules\Certification\Http\Requests\CertificateRequest;
use Illuminate\Http\UploadedFile;

class CertificateDTO
{
    protected string $title;
    public function getTitle(): string
    {
        return $this->title;
    }

    protected UploadedFile|null $image;
    public function getImage(): ?UploadedFile
    {
        return $this->image;
    }
    protected int|null $show_in_first_page;
    public function getShowInFirstPage(): ?int
    {

        return $this->show_in_first_page;
    }


    public static function fromRequest(CertificateRequest $request)
    {
        $self = new self();
        $self->title = $request->get('title');
        $self->image = $request->file('image');
        $self->show_in_first_page = @$request->has('show_in_first_page') ? 1 : 0;
        return $self;
    }
}
