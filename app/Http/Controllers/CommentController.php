<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Modules\Comment\DTO\CommentDTO;
use App\Modules\Comment\Http\Requests\CommentRequest;
use App\Modules\Comment\Services\CommentService;

class CommentController extends Controller
{
    public function postComment(CommentRequest $request)
    {
        CommentService::create(CommentDTO::fromRequest($request));
        return Redirect::back()->with('success', 'نظر شما با موفقیت ثبت شد');
    }

}
