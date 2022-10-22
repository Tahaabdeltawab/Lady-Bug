<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCommentAPIRequest;
use App\Http\Requests\API\UpdateCommentAPIRequest;
use App\Models\Comment;
use App\Repositories\CommentRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\CommentResource;
use Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Repositories\AssetRepository;


/**
 * Class CommentController
 * @package App\Http\Controllers\API
 */

class CommentAPIController extends AppBaseController
{
    /** @var  CommentRepository */
    private $commentRepository;
    private $assetRepository;

    public function __construct(CommentRepository $commentRepo, AssetRepository $assetRepo)
    {
        $this->commentRepository = $commentRepo;
        $this->assetRepository = $assetRepo;
    }

    public function index(Request $request)
    {
        $comments = $this->commentRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => CommentResource::collection($comments)], 'Comments retrieved successfully');
    }

    public function store(/* CreateCommentAPI */Request $request)
    {
        try
        {

            $validator = Validator::make($request->all(), [
                'content' => ['requiredIf:assets,null'],
                'post_id' => ['required', 'integer', 'exists:posts,id'],
                'parent_id' => ['nullable', 'integer', 'exists:comments,id'],
                'assets' => ['nullable','array'],
                'assets.*' => ['nullable', 'max:5000', 'mimes:jpeg,jpg,png,svg']
            ]);

            if($validator->fails()){
                $errors = $validator->errors();

                return $this->sendError(json_encode($errors), 777);
            }

            $data['commenter_id'] = auth()->id();
            $data['content'] = $request->content;
            $data['post_id'] = $request->post_id;
            $data['parent_id'] = $request->parent_id;

            $comment = $this->commentRepository->save_localized($data);
            $comment->post->updateReactions();
            if($assets = $request->file('assets'))
            {
                foreach($assets as $asset)
                {
                    $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($asset, 'comment');
                    $saved_asset[] = $comment->assets()->create($oneasset);
                }
            }
            if($comment->commenter_id != $comment->post->author_id)
            $comment->post->author->notify(new \App\Notifications\TimelineInteraction($comment));

            $comment->loadMissing(['siblings.commenter']);
            foreach ($comment->siblings()->get() as $sibling) {
                $sibling->commenter->notify(new \App\Notifications\TimelineInteraction($comment, 'same_post_comment'));
            }

            return $this->sendResponse(new CommentResource($comment), __('Comment saved successfully'));
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }

    }



    // L I K E S  &&  D I S L I K E S

    public function toggle_like($id)
    {
        try
        {
            $comment = $this->commentRepository->find($id);

            if (empty($comment))
            {
                return $this->sendError('Comment not found');
            }

            $like = auth()->user()->toggleLike($comment);
            $like_model = config('like.like_model');
            if($like instanceOf  $like_model)
            {
                $msg = 'Comment liked successfully';
                if($like->user_id != $comment->commenter_id)
                $comment->commenter->notify(new \App\Notifications\TimelineInteraction($like));
            }
            else
            {
                $msg = 'Comment like removed successfully';
            }

            return $this->sendSuccess($msg);
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }



    public function toggle_dislike($id)
    {
        try
        {
            $comment = $this->commentRepository->find($id);

            if (empty($comment))
            {
                return $this->sendError('Comment not found');
            }
            $dislike = auth()->user()->toggleDislike($comment);
            $like_model = config('like.like_model');
            if($dislike instanceOf  $like_model)
            {
                $msg = 'Comment disliked successfully';
                if($dislike->user_id != $comment->commenter_id)
                $comment->commenter->notify(new \App\Notifications\TimelineInteraction($dislike));
            }
            else
            {
                $msg = 'Comment dislike removed successfully';
            }

            return $this->sendSuccess($msg);
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }

    public function show($id)
    {
        /** @var Comment $comment */
        $comment = $this->commentRepository->find($id);

        if (empty($comment)) {
            return $this->sendError('Comment not found');
        }

        return $this->sendResponse(new CommentResource($comment), 'Comment retrieved successfully');
    }

    public function update($id, /* UpdateCommentAPI */Request $request) //don't change it to create request please as it differs in validation
    {
        try
        {
            $comment = $this->commentRepository->find($id);

            if (empty($comment)) {
                return $this->sendError('comment not found');
            }

            $validator = Validator::make($request->all(), [
                'content' => ['requiredIf:assets,null'],
                'assets' => ['nullable','array'],
                'assets.*' => ['nullable', 'max:5000', 'mimes:jpeg,jpg,png,svg']
            ]);

            if($validator->fails()){
                $errors = $validator->errors();

                return $this->sendError(json_encode($errors), 777);
            }

            $data['content'] = $request->content;

            $comment = $this->commentRepository->save_localized($data, $id);

            if($assets = $request->file('assets'))
            {
                foreach ($comment->assets as $ass) {
                    $ass->delete();
                }
                foreach($assets as $asset)
                {
                    $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($asset, 'comment');
                    $saved_asset[] = $comment->assets()->create($oneasset);
                }
            }

            return $this->sendResponse(new CommentResource($comment), __('Comment saved successfully'));
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try
        {
        /** @var Comment $comment */
        $comment = $this->commentRepository->find($id);

        if (empty($comment)) {
            return $this->sendError('Comment not found');
        }
        // $comment
        $comment->delete();
        foreach($comment->assets as $ass){
            $ass->delete();
        }

        $comment->post->updateReactions();

          return $this->sendSuccess('Model deleted successfully');
        }
        catch(\Throwable $th)
        {
            if ($th instanceof \Illuminate\Database\QueryException)
            return $this->sendError('Model cannot be deleted as it is associated with other models');
            else
            return $this->sendError('Error deleting the model');
        }
    }
}
