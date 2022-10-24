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

        $this->middleware('permission:post_types.store')->only(['store']);
        $this->middleware('permission:post_types.update')->only(['update']);
        $this->middleware('permission:post_types.destroy')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $postTypes = $this->postTypeRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => PostTypeResource::collection($postTypes)], 'Post Types retrieved successfully');
    }

    public function store(CreatePostTypeAPIRequest $request)
    {
        $input = $request->validated();

        $postType = $this->postTypeRepository->create($input);

        return $this->sendResponse(new PostTypeResource($postType), 'Post Type saved successfully');
    }

    public function show($id)
    {
        /** @var PostType $postType */
        $postType = $this->postTypeRepository->find($id);

        if (empty($postType)) {
            return $this->sendError('Post Type not found');
        }

        return $this->sendResponse(new PostTypeResource($postType), 'Post Type retrieved successfully');
    }

    public function update($id, CreatePostTypeAPIRequest $request)
    {
        $input = $request->validated();

        /** @var PostType $postType */
        $postType = $this->postTypeRepository->find($id);

        if (empty($postType)) {
            return $this->sendError('Post Type not found');
        }

        $postType = $this->postTypeRepository->update($input, $id);

        return $this->sendResponse(new PostTypeResource($postType), 'PostType updated successfully');
    }

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
