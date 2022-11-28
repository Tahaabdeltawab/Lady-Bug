<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateTaxonomyAPIRequest;
use App\Http\Requests\API\UpdateTaxonomyAPIRequest;
use App\Models\Taxonomy;
use App\Repositories\TaxonomyRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\TaxonomyResource;
use Response;

/**
 * Class TaxonomyController
 * @package App\Http\Controllers\API
 */

class TaxonomyAPIController extends AppBaseController
{
    /** @var  TaxonomyRepository */
    private $taxonomyRepository;

    public function __construct(TaxonomyRepository $taxonomyRepo)
    {
        $this->taxonomyRepository = $taxonomyRepo;
    }

    /**
     * Display a listing of the Taxonomy.
     * GET|HEAD /taxonomies
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $taxonomies = $this->taxonomyRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page'),
            $request->get('perPage')
        );

        return $this->sendResponse(['all' => TaxonomyResource::collection($taxonomies['all']), 'meta' => $taxonomies['meta']], 'Taxonomies retrieved successfully');
    }

    /**
     * Store a newly created Taxonomy in storage.
     * POST /taxonomies
     *
     * @param CreateTaxonomyAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateTaxonomyAPIRequest $request)
    {
        $input = $request->validated();
        $taxonomy = Taxonomy::updateOrCreate(['farmed_type_id' => $request->farmed_type_id], $input);

        return $this->sendResponse(new TaxonomyResource($taxonomy), 'Taxonomy saved successfully');
    }

    /**
     * Display the specified Taxonomy.
     * GET|HEAD /taxonomies/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Taxonomy $taxonomy */
        $taxonomy = $this->taxonomyRepository->find($id);

        if (empty($taxonomy)) {
            return $this->sendError('Taxonomy not found');
        }

        return $this->sendResponse(new TaxonomyResource($taxonomy), 'Taxonomy retrieved successfully');
    }

    public function by_ft_id($id)
    {
        $taxonomy = Taxonomy::where('farmed_type_id', $id)->first();

        if (empty($taxonomy)) {
            return $this->sendError('taxonomy not found');
        }

        return $this->sendResponse(new TaxonomyResource($taxonomy), 'taxonomy retrieved successfully');
    }

    /**
     * Update the specified Taxonomy in storage.
     * PUT/PATCH /taxonomies/{id}
     *
     * @param int $id
     * @param UpdateTaxonomyAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTaxonomyAPIRequest $request)
    {
        $input = $request->validated();

        /** @var Taxonomy $taxonomy */
        $taxonomy = $this->taxonomyRepository->find($id);

        if (empty($taxonomy)) {
            return $this->sendError('Taxonomy not found');
        }

        $taxonomy = $this->taxonomyRepository->update($input, $id);

        return $this->sendResponse(new TaxonomyResource($taxonomy), 'Taxonomy updated successfully');
    }

    /**
     * Remove the specified Taxonomy from storage.
     * DELETE /taxonomies/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        try
        {
        /** @var Taxonomy $taxonomy */
        $taxonomy = $this->taxonomyRepository->find($id);

        if (empty($taxonomy)) {
            return $this->sendError('Taxonomy not found');
        }

        $taxonomy->delete();

        return $this->sendSuccess('Taxonomy deleted successfully');
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
