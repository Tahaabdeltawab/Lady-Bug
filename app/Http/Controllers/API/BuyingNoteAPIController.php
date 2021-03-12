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
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/buyingNotes",
     *      summary="Get a listing of the BuyingNotes.",
     *      tags={"BuyingNote"},
     *      description="Get all BuyingNotes",
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
     *                  @SWG\Items(ref="#/definitions/BuyingNote")
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
        $buyingNotes = $this->buyingNoteRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => BuyingNoteResource::collection($buyingNotes)], 'Buying Notes retrieved successfully');
    }

    /**
     * @param CreateBuyingNoteAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/buyingNotes",
     *      summary="Store a newly created BuyingNote in storage",
     *      tags={"BuyingNote"},
     *      description="Store BuyingNote",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="BuyingNote that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/BuyingNote")
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
     *                  ref="#/definitions/BuyingNote"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateBuyingNoteAPIRequest $request)
    {
        $input = $request->validated();

        $buyingNote = $this->buyingNoteRepository->save_localized($input);

        return $this->sendResponse(new BuyingNoteResource($buyingNote), 'Buying Note saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/buyingNotes/{id}",
     *      summary="Display the specified BuyingNote",
     *      tags={"BuyingNote"},
     *      description="Get BuyingNote",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of BuyingNote",
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
     *                  ref="#/definitions/BuyingNote"
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
        /** @var BuyingNote $buyingNote */
        $buyingNote = $this->buyingNoteRepository->find($id);

        if (empty($buyingNote)) {
            return $this->sendError('Buying Note not found');
        }

        return $this->sendResponse(new BuyingNoteResource($buyingNote), 'Buying Note retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateBuyingNoteAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/buyingNotes/{id}",
     *      summary="Update the specified BuyingNote in storage",
     *      tags={"BuyingNote"},
     *      description="Update BuyingNote",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of BuyingNote",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="BuyingNote that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/BuyingNote")
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
     *                  ref="#/definitions/BuyingNote"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateBuyingNoteAPIRequest $request)
    {
        $input = $request->validated();

        /** @var BuyingNote $buyingNote */
        $buyingNote = $this->buyingNoteRepository->find($id);

        if (empty($buyingNote)) {
            return $this->sendError('Buying Note not found');
        }

        $buyingNote = $this->buyingNoteRepository->save_localized($input, $id);

        return $this->sendResponse(new BuyingNoteResource($buyingNote), 'BuyingNote updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/buyingNotes/{id}",
     *      summary="Remove the specified BuyingNote from storage",
     *      tags={"BuyingNote"},
     *      description="Delete BuyingNote",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of BuyingNote",
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
        /** @var BuyingNote $buyingNote */
        $buyingNote = $this->buyingNoteRepository->find($id);

        if (empty($buyingNote)) {
            return $this->sendError('Buying Note not found');
        }

        $buyingNote->delete();

        return $this->sendSuccess('Buying Note deleted successfully');
    }
}
