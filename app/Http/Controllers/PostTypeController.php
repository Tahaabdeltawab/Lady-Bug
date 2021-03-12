<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostTypeRequest;
use App\Http\Requests\UpdatePostTypeRequest;
use App\Repositories\PostTypeRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class PostTypeController extends AppBaseController
{
    /** @var  PostTypeRepository */
    private $postTypeRepository;

    public function __construct(PostTypeRepository $postTypeRepo)
    {
        $this->postTypeRepository = $postTypeRepo;
    }

    /**
     * Display a listing of the PostType.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $postTypes = $this->postTypeRepository->all();

        return view('post_types.index')
            ->with('postTypes', $postTypes);
    }

    /**
     * Show the form for creating a new PostType.
     *
     * @return Response
     */
    public function create()
    {
        return view('post_types.create');
    }

    /**
     * Store a newly created PostType in storage.
     *
     * @param CreatePostTypeRequest $request
     *
     * @return Response
     */
    public function store(CreatePostTypeRequest $request)
    {
        $input = $request->all();

        $postType = $this->postTypeRepository->create($input);

        Flash::success('Post Type saved successfully.');

        return redirect(route('postTypes.index'));
    }

    /**
     * Display the specified PostType.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $postType = $this->postTypeRepository->find($id);

        if (empty($postType)) {
            Flash::error('Post Type not found');

            return redirect(route('postTypes.index'));
        }

        return view('post_types.show')->with('postType', $postType);
    }

    /**
     * Show the form for editing the specified PostType.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $postType = $this->postTypeRepository->find($id);

        if (empty($postType)) {
            Flash::error('Post Type not found');

            return redirect(route('postTypes.index'));
        }

        return view('post_types.edit')->with('postType', $postType);
    }

    /**
     * Update the specified PostType in storage.
     *
     * @param int $id
     * @param UpdatePostTypeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePostTypeRequest $request)
    {
        $postType = $this->postTypeRepository->find($id);

        if (empty($postType)) {
            Flash::error('Post Type not found');

            return redirect(route('postTypes.index'));
        }

        $postType = $this->postTypeRepository->update($request->all(), $id);

        Flash::success('Post Type updated successfully.');

        return redirect(route('postTypes.index'));
    }

    /**
     * Remove the specified PostType from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $postType = $this->postTypeRepository->find($id);

        if (empty($postType)) {
            Flash::error('Post Type not found');

            return redirect(route('postTypes.index'));
        }

        $this->postTypeRepository->delete($id);

        Flash::success('Post Type deleted successfully.');

        return redirect(route('postTypes.index'));
    }
}
