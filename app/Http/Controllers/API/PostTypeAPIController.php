<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePostTypeAPIRequest;
use App\Http\Requests\API\UpdatePostTypeAPIRequest;
use App\Models\PostType;
use App\Repositories\PostTypeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\PostTypeResource;
use Response;

/**
 * Class PostTypeController
 * @package App\Http\Controllers\API
 */

class PostTypeAPIController extends AppBaseController
{
    /** @var  PostTypeRepository */
    private $postTypeRepository;

    public function __construct(PostTypeRepository $postTypeRepo)
    {
        $this->postTypeRepository = $postTypeRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/postTypes",
     *      summary="Get a listing of the PostTypes.",
     *      tags={"PostType"},
     *      description="Get all PostTypes",
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
     *                  @SWG\Items(ref="#/definitions/PostType")
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
        $postTypes = $this->postTypeRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => PostTypeResource::collection($postTypes)], 'Post Types retrieved successfully');
    }

    /**
     * @param CreatePostTypeAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/postTypes",
     *      summary="Store a newly created PostType in storage",
     *      tags={"PostType"},
     *      description="Store PostType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="PostType that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/PostType")
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
     *                  ref="#/definitions/PostType"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreatePostTypeAPIRequest $request)
    {
        $input = $request->validated();

        $postType = $this->postTypeRepository->save_localized($input);

        return $this->sendResponse(new PostTypeResource($postType), 'Post Type saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/postTypes/{id}",
     *      summary="Display the specified PostType",
     *      tags={"PostType"},
     *      description="Get PostType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of PostType",
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
     *                  ref="#/definitions/PostType"
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
        /** @var PostType $postType */
        $postType = $this->postTypeRepository->find($id);

        if (empty($postType)) {
            return $this->sendError('Post Type not found');
        }

        return $this->sendResponse(new PostTypeResource($postType), 'Post Type retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdatePostTypeAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/postTypes/{id}",
     *      summary="Update the specified PostType in storage",
     *      tags={"PostType"},
     *      description="Update PostType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of PostType",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="PostType that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/PostType")
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
     *                  ref="#/definitions/PostType"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, CreatePostTypeAPIRequest $request)
    {
        $input = $request->validated();

        /** @var PostType $postType */
        $postType = $this->postTypeRepository->find($id);

        if (empty($postType)) {
            return $this->sendError('Post Type not found');
        }

        $postType = $this->postTypeRepository->save_localized($input, $id);

        return $this->sendResponse(new PostTypeResource($postType), 'PostType updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/postTypes/{id}",
     *      summary="Remove the specified PostType from storage",
     *      tags={"PostType"},
     *      description="Delete PostType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of PostType",
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
        /** @var PostType $postType */
        $postType = $this->postTypeRepository->find($id);

        if (empty($postType)) {
            return $this->sendError('Post Type not found');
        }

        $postType->delete();

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
