<?php
namespace App\Modules\Comment\Services;

use App\Modules\Comment\DTO\AdminCommentDTO;
use App\Modules\Comment\DTO\AdminReplyDTO;
use App\Modules\Comment\Entities\Comment;
use App\Modules\Comment\DTO\CommentDTO;

class CommentService
{

    public static function create(CommentDTO $commentDTO)
    {
        Comment::create([
            'name' => $commentDTO->getName(),
            'mobile' => $commentDTO->getMobile(),
            'content' => $commentDTO->getContent(),
            'commentable_id' => $commentDTO->getCommentableId(),
            'commentable_type' => $commentDTO->getCommentableType(),
            'reply_id' => $commentDTO->getReplyId(),
            'rate' => $commentDTO->getRate(),
            'user_id' => auth()->id(),
        ]);

    }
    public function update(int $id, AdminCommentDTO $commentDTO)
    {
        $comment= Comment::findOrfail($id);

        $comment->update([

            'content' => $commentDTO->getContent(),
            'status' => $commentDTO->getStatus(),
            'name' => $commentDTO->getName(),
            'rate'=>$commentDTO->getRate(),

        ]);

    }
    public function adminReply(AdminReplyDTO $commentDTO)
    {

        Comment::create([
            'name' => $commentDTO->getName(),
            'mobile' => $commentDTO->getMobile(),
            'content' => $commentDTO->getContent(),
            'commentable_id' => $commentDTO->getCommentableId(),
            'commentable_type' => $commentDTO->getCommentableType(),
            'reply_id' => $commentDTO->getReplyId(),
            'user_id' => $commentDTO->getUserId(),
            'rate' => $commentDTO->getRate(),
            'status' => $commentDTO->getStatus(),
        ]);

    }
    public function updateStatus(int $id)
    {
        $comment = Comment::findOrfail($id);
        $comment->status = $comment->status == 1 ? 0 : 1;
        $comment->save();
    }

    public function destroy(int $id)
    {
        $Service = Comment::find($id);
        Comment::destroy($id);
    }
}
