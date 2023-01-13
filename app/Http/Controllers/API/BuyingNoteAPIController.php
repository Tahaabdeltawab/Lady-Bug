<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateBuyingNoteAPIRequest;
use App\Http\Requests\API\UpdateBuyingNoteAPIRequest;
use App\Models\BuyingNote;
use App\Repositories\BuyingNoteRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\BuyingNoteResource;
use Response;

/**
 * Class BuyingNoteController
 * @package App\Http\Controllers\API
 */

class BuyingNoteAPIController extends AppBaseController
{
    /** @var  BuyingNoteRepository */
    private $buyingNoteRepository;

    public function __construct(BuyingNoteRepository $buyingNoteRepo)
    {
        $this->buyingNoteRepository = $buyingNoteRepo;

        $this->middleware('permission:buying_notes.store')->only(['store']);
        $this->middleware('permission:buying_notes.update')->only(['update']);
        $this->middleware('permission:buying_notes.destroy')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $buyingNotes = $this->buyingNoteRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page') ?? 1,
            $request->get('perPage')
        );

        return $this->sendResponse(['all' => BuyingNoteResource::collection($buyingNotes['all']), 'meta' => $buyingNotes['meta']], 'Buying Notes retrieved successfully');
    }

    public function store(CreateBuyingNoteAPIRequest $request)
    {
        $input = $request->validated();

        $buyingNote = $this->buyingNoteRepository->create($input);

        return $this->sendResponse(new BuyingNoteResource($buyingNote), 'Buying Note saved successfully');
    }

    public function show($id)
    {
        /** @var BuyingNote $buyingNote */
        $buyingNote = $this->buyingNoteRepository->find($id);

        if (empty($buyingNote)) {
            return $this->sendError('Buying Note not found');
        }

        return $this->sendResponse(new BuyingNoteResource($buyingNote), 'Buying Note retrieved successfully');
    }

    public function update($id, CreateBuyingNoteAPIRequest $request)
    {
        $input = $request->validated();

        /** @var BuyingNote $buyingNote */
        $buyingNote = $this->buyingNoteRepository->find($id);

        if (empty($buyingNote)) {
            return $this->sendError('Buying Note not found');
        }

        $buyingNote = $this->buyingNoteRepository->update($input, $id);

        return $this->sendResponse(new BuyingNoteResource($buyingNote), 'BuyingNote updated successfully');
    }

    public function destroy($id)
    {
        try
        {
        /** @var BuyingNote $buyingNote */
        $buyingNote = $this->buyingNoteRepository->find($id);

        if (empty($buyingNote)) {
            return $this->sendError('Buying Note not found');
        }

        $buyingNote->delete();

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
