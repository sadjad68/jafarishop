<?php

namespace App\Modules\Comment\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
use App\Modules\Comment\DTO\AdminCommentDTO;
use App\Modules\Comment\DTO\AdminReplyDTO;
use App\Modules\Comment\DTO\CommentDTO;
use App\Modules\Comment\Entities\Comment;
use App\Modules\Comment\Filters\CommentFilter;
use App\Modules\Comment\Http\Requests\AdminCommentRequest;
use App\Modules\Comment\Services\CommentService;
use App\Modules\Product\Entities\Product;

class CommentController extends Controller
{

    protected $commentService;
    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $query = Comment::query();

        if ($request->has('filter')) {
            $query = app(CommentFilter::class)->apply($query, $request->all());
        }

        $comment = $query
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.comment.index', compact('comment'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data = Comment::findOrfail($id);
        return view('admin.comment.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update($id, AdminCommentRequest $request)
    {
        $this->commentService->update($id, AdminCommentDTO::fromRequest($request));
        return redirect()->route('admin.comment.index', ['page' => $request->get('fallback_page',1)])
            ->with('success', 'کامنت ویرایش شد.');
    }

    public function updateStatus($id)
    {
        $this->commentService->updateStatus($id);
        return Redirect::back()->with('success', 'وضعیت ویرایش شد.');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $this->commentService->destroy($id);
        return Redirect::back()->with('success', 'آیتم موردنظر با موفقیت حذف شد');
    }
    public function reply(AdminCommentRequest $request){
        $this->commentService->adminReply(AdminReplyDTO::fromRequest($request));
        return Redirect::back()->with('success', 'پاسخ موردنظر با موفقیت ثبت شد');

    }
    public function storeProduct(Request $request)
    {
        // هم single هم bulk
        $items = $request->input('comments');
        if (!is_array($items)) {
            $items = [$request->all()];
        }

        $allowed = [
            'mobile','user_id','name','content',
            'commentable_id','commentable_type',
            'reply_id','status','rate',
        ];

        $created = [];
        $errors  = [];

        foreach ($items as $i => $raw) {
            try {
                $data = Arr::only($raw, $allowed);
                $data['status'] = 0;
                Comment::create([
                    'commentable_type' => Product::class,
                    'commentable_id'   => $data['commentable_id'],
                    'content'          => $data['content'],
                    'name'             => $data['name'],
                    'mobile'           => $data['mobile'],
                    'rate'             => $data['rate'],
                    'status'           => $data['status'],
                ]);
            } catch (\Throwable $e) {
                $errors[] = [
                    'index'   => $i,
                    'message' => config('app.debug') ? $e->getMessage() : 'خطا در ثبت آیتم',
                ];
            }
        }

        $code = count($errors) ? 207 : 201;

        return response()->json([
            'message' => count($errors) ? 'برخی آیتم‌ها ثبت نشد' : 'ثبت شد',
            'data'    => $created,
            'errors'  => count($errors) ? $errors : null,
        ], $code);
    }
}
