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

/**
 * Class CommentController
 * @package App\Http\Controllers\API
 */

class CommentAPIController extends AppBaseController
{
    /** @var  CommentRepository */
    private $commentRepository;

    public function __construct(CommentRepository $commentRepo)
    {
        $this->commentRepository = $commentRepo;
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
    public function store(CreateCommentAPIRequest $request)
    {
        $input = $request->validated();

        $comment = $this->commentRepository->save_localized($input);

        return $this->sendResponse(new CommentResource($comment), 'Comment saved successfully');
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
    public function update($id, UpdateCommentAPIRequest $request)
    {
        $input = $request->validated();

        /** @var Comment $comment */
        $comment = $this->commentRepository->find($id);

        if (empty($comment)) {
            return $this->sendError('Comment not found');
        }

        $comment = $this->commentRepository->save_localized($input, $id);

        return $this->sendResponse(new CommentResource($comment), 'Comment updated successfully');
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
        /** @var Comment $comment */
        $comment = $this->commentRepository->find($id);

        if (empty($comment)) {
            return $this->sendError('Comment not found');
        }

        $comment->delete();

        return $this->sendSuccess('Comment deleted successfully');
    }
}
