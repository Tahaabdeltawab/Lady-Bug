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

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/comments",
     *      summary="Get a listing of the Comments.",
     *      tags={"Comment"},
     *      description="Get all Comments",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/Comment")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function index(Request $request)
    {
        $comments = $this->commentRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => CommentResource::collection($comments)], 'Comments retrieved successfully');
    }

    /**
     * @param CreateCommentAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/comments",
     *      summary="Store a newly created Comment in storage",
     *      tags={"Comment"},
     *      description="Store Comment",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Comment that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Comment")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Comment"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(/* CreateCommentAPI */Request $request)
    {
        try
        {

            $validator = Validator::make($request->all(), [
                'content' => ['requiredIf:assets,null'],
                'post_id' => ['required', 'integer', 'exists:posts,id'],
                'parent_id' => ['nullable', 'integer', 'exists:comments,id'],
                'assets' => ['nullable','array'],
                'assets.*' => ['nullable', 'max:20000', 'mimes:jpeg,jpg,png,svg']
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
               /*  if(!is_array($assets))
                {
                    $currentDate = Carbon::now()->toDateString();
                    $assetsname = 'comment-'.$currentDate.'-'.uniqid().'.'.$assets->getClientOriginalExtension();
                    $assetssize = $assets->getSize(); //size in bytes 1k = 1000bytes
                    $assetsmime = $assets->getClientMimeType();

                    $path = $assets->storeAs('assets/comments', $assetsname, 's3');
                    // $path = Storage::disk('s3')->putFileAs('assets/images', $asset, $assetname);

                    $url  = Storage::disk('s3')->url($path);

                    $saved_asset = $comment->assets()->create([
                        'asset_name'        => $assetsname,
                        'asset_url'         => $url,
                        'asset_size'        => $assetssize,
                        'asset_mime'        => $assetsmime,
                    ]);
                }
                else
                { */
                    foreach($assets as $asset)
                    {
                        //ERROR YOU CANNOT PASS UPLOADED FILE TO THE QUEUE
                        // dispatch(new \App\Jobs\Upload($asset, $comment));
                        $currentDate = Carbon::now()->toDateString();
                        $assetname = 'comment-'.$currentDate.'-'.uniqid().'.'.$asset->getClientOriginalExtension();
                        $assetsize = $asset->getSize(); //size in bytes 1k = 1000bytes
                        $assetmime = $asset->getClientMimeType();

                        $path = $asset->storeAs('assets/comments', $assetname, 's3');
                        // $path = Storage::disk('s3')->putFileAs('assets/images', $asset, $assetname);

                        $url  = Storage::disk('s3')->url($path);

                        $saved_asset[] = $comment->assets()->create([
                            'asset_name'        => $assetname,
                            'asset_url'         => $url,
                            'asset_size'        => $assetsize,
                            'asset_mime'        => $assetmime,
                        ]);
                    }
                // }
            }

            $comment->post->author->notify(new \App\Notifications\TimelineInteraction($comment));
            return $this->sendResponse(new CommentResource($comment), __('Comment saved successfully'));
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }

    }



    //  //  //  //  //  L I K E S  &&  D I S L I K E S  //  //  //  //  //

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
                $comment->post->author->notify(new \App\Notifications\TimelineInteraction($like));
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

            $msg = auth()->user()->toggleDislike($comment);
            return $this->sendSuccess('Comment '.$msg);
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/comments/{id}",
     *      summary="Display the specified Comment",
     *      tags={"Comment"},
     *      description="Get Comment",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Comment",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Comment"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        /** @var Comment $comment */
        $comment = $this->commentRepository->find($id);

        if (empty($comment)) {
            return $this->sendError('Comment not found');
        }

        return $this->sendResponse(new CommentResource($comment), 'Comment retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateCommentAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/comments/{id}",
     *      summary="Update the specified Comment in storage",
     *      tags={"Comment"},
     *      description="Update Comment",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Comment",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Comment that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Comment")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Comment"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
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
                'assets.*' => ['nullable', 'max:20000', 'mimes:jpeg,jpg,png,svg']
            ]);

            if($validator->fails()){
                $errors = $validator->errors();

                return $this->sendError(json_encode($errors), 777);
            }

            $data['content'] = $request->content;

            $comment = $this->commentRepository->save_localized($data, $id);

            if($assets = $request->file('assets'))
            {
               /*  if(!is_array($assets))
                {
                    $currentDate = Carbon::now()->toDateString();
                    $assetsname = 'comment-'.$currentDate.'-'.uniqid().'.'.$assets->getClientOriginalExtension();
                    $assetssize = $assets->getSize(); //size in bytes 1k = 1000bytes
                    $assetsmime = $assets->getClientMimeType();

                    $path = $assets->storeAs('assets/comments', $assetsname, 's3');
                    // $path = Storage::disk('s3')->putFileAs('assets/images', $asset, $assetname);

                    $url  = Storage::disk('s3')->url($path);

                    $comment->assets()->delete();
                    $saved_asset = $comment->assets()->create([
                        'asset_name'        => $assetsname,
                        'asset_url'         => $url,
                        'asset_size'        => $assetssize,
                        'asset_mime'        => $assetsmime,
                    ]);
                }
                else
                { */
                    $comment->assets()->delete();

                    foreach($assets as $asset)
                    {
                        //ERROR YOU CANNOT PASS UPLOADED FILE TO THE QUEUE
                        // dispatch(new \App\Jobs\Upload($asset, $comment));
                        $currentDate = Carbon::now()->toDateString();
                        $assetname = 'comment-'.$currentDate.'-'.uniqid().'.'.$asset->getClientOriginalExtension();
                        $assetsize = $asset->getSize(); //size in bytes 1k = 1000bytes
                        $assetmime = $asset->getClientMimeType();

                        $path = $asset->storeAs('assets/comments', $assetname, 's3');
                        // $path = Storage::disk('s3')->putFileAs('assets/images', $asset, $assetname);

                        $url  = Storage::disk('s3')->url($path);

                        $saved_asset[] = $comment->assets()->create([
                            'asset_name'        => $assetname,
                            'asset_url'         => $url,
                            'asset_size'        => $assetsize,
                            'asset_mime'        => $assetmime,
                        ]);
                    }
                // }
            }
            else
            {
                // $comment->assets()->sync([]);
            }

            return $this->sendResponse(new CommentResource($comment), __('Comment saved successfully'));
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/comments/{id}",
     *      summary="Remove the specified Comment from storage",
     *      tags={"Comment"},
     *      description="Delete Comment",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Comment",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
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
