<?php

namespace App\Modules\Comment\DTO;

use App\Modules\Comment\Http\Requests\CommentRequest;
use App\Modules\General\Helper\NumberHelper;



class CommentDTO
{


    protected string $name;
    public function getName(): string
    {
        return $this->name;
    }
    protected string $mobile;
    public function getMobile(): string
    {
        return $this->mobile;
    }
    protected string $content;
    public function getContent(): string
    {
        return $this->content;
    }
    protected int $commentable_id;
    public function getCommentableId(): int
    {
        return $this->commentable_id;
    }
    protected string $commentable_type;
    public function getCommentableType(): string
    {
        return $this->commentable_type;
    }
    protected int|null $reply_id;
    public function getReplyId(): int|null
    {
        return $this->reply_id;
    }
    protected int|null $rate;
    public function getRate(): int|null
    {
        return $this->rate;
    }

    public static function fromRequest(CommentRequest $request)
    {
        $self = new self();
        $self->name = $request->get('name');
        $self->mobile = NumberHelper::persian2LatinDigit($request->get('mobile'));
        $self->content = $request->get('content');
        $self->commentable_id = $request->get('commentable_id');
        $self->commentable_type = $request->get('commentable_type');
        $self->reply_id = @$request->get('reply_id');
        $self->rate = @$request->get('rate');

        return $self;
    }
}
