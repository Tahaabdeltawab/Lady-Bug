<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBuyingNoteRequest;
use App\Http\Requests\UpdateBuyingNoteRequest;
use App\Repositories\BuyingNoteRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class BuyingNoteController extends AppBaseController
{
    /** @var  BuyingNoteRepository */
    private $buyingNoteRepository;

    public function __construct(BuyingNoteRepository $buyingNoteRepo)
    {
        $this->buyingNoteRepository = $buyingNoteRepo;
    }

    /**
     * Display a listing of the BuyingNote.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $buyingNotes = $this->buyingNoteRepository->all();

        return view('buying_notes.index')
            ->with('buyingNotes', $buyingNotes);
    }

    /**
     * Show the form for creating a new BuyingNote.
     *
     * @return Response
     */
    public function create()
    {
        return view('buying_notes.create');
    }

    /**
     * Store a newly created BuyingNote in storage.
     *
     * @param CreateBuyingNoteRequest $request
     *
     * @return Response
     */
    public function store(CreateBuyingNoteRequest $request)
    {
        $input = $request->all();

        $buyingNote = $this->buyingNoteRepository->create($input);

        Flash::success('Buying Note saved successfully.');

        return redirect(route('buyingNotes.index'));
    }

    /**
     * Display the specified BuyingNote.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $buyingNote = $this->buyingNoteRepository->find($id);

        if (empty($buyingNote)) {
            Flash::error('Buying Note not found');

            return redirect(route('buyingNotes.index'));
        }

        return view('buying_notes.show')->with('buyingNote', $buyingNote);
    }

    /**
     * Show the form for editing the specified BuyingNote.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $buyingNote = $this->buyingNoteRepository->find($id);

        if (empty($buyingNote)) {
            Flash::error('Buying Note not found');

            return redirect(route('buyingNotes.index'));
        }

        return view('buying_notes.edit')->with('buyingNote', $buyingNote);
    }

    /**
     * Update the specified BuyingNote in storage.
     *
     * @param int $id
     * @param UpdateBuyingNoteRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBuyingNoteRequest $request)
    {
        $buyingNote = $this->buyingNoteRepository->find($id);

        if (empty($buyingNote)) {
            Flash::error('Buying Note not found');

            return redirect(route('buyingNotes.index'));
        }

        $buyingNote = $this->buyingNoteRepository->update($request->all(), $id);

        Flash::success('Buying Note updated successfully.');

        return redirect(route('buyingNotes.index'));
    }

    /**
     * Remove the specified BuyingNote from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $buyingNote = $this->buyingNoteRepository->find($id);

        if (empty($buyingNote)) {
            Flash::error('Buying Note not found');

            return redirect(route('buyingNotes.index'));
        }

        $this->buyingNoteRepository->delete($id);

        Flash::success('Buying Note deleted successfully.');

        return redirect(route('buyingNotes.index'));
    }
}
