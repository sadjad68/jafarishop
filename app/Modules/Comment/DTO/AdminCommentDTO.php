<?php

namespace App\Modules\Comment\DTO;

use App\Modules\Comment\Http\Requests\AdminCommentRequest;




class AdminCommentDTO
{

    protected string $content;
    public function getContent(): string
    {
        return $this->content;
    }

    protected int|null $status;
    public function getStatus(): int|null
    {

        return $this->status;
    }

    protected string $name;

    public function getName(): string
    {
        return $this->name;
    }

    protected $rate;


    public function getRate()
    {
        return $this->rate;
    }

    public static function fromRequest(AdminCommentRequest $request)
    {
        $self = new self();

        $self->content = $request->get('content');
        $self->status = $request->has('status') ? 1 : 0;
        $self->name = $request->get('name');
        $self->rate = $request->get('rate');

        return $self;
    }
}
