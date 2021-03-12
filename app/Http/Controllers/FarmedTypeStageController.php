<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFarmedTypeStageRequest;
use App\Http\Requests\UpdateFarmedTypeStageRequest;
use App\Repositories\FarmedTypeStageRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class FarmedTypeStageController extends AppBaseController
{
    /** @var  FarmedTypeStageRepository */
    private $farmedTypeStageRepository;

    public function __construct(FarmedTypeStageRepository $farmedTypeStageRepo)
    {
        $this->farmedTypeStageRepository = $farmedTypeStageRepo;
    }

    /**
     * Display a listing of the FarmedTypeStage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $farmedTypeStages = $this->farmedTypeStageRepository->all();

        return view('farmed_type_stages.index')
            ->with('farmedTypeStages', $farmedTypeStages);
    }

    /**
     * Show the form for creating a new FarmedTypeStage.
     *
     * @return Response
     */
    public function create()
    {
        return view('farmed_type_stages.create');
    }

    /**
     * Store a newly created FarmedTypeStage in storage.
     *
     * @param CreateFarmedTypeStageRequest $request
     *
     * @return Response
     */
    public function store(CreateFarmedTypeStageRequest $request)
    {
        $input = $request->all();

        $farmedTypeStage = $this->farmedTypeStageRepository->create($input);

        Flash::success('Farmed Type Stage saved successfully.');

        return redirect(route('farmedTypeStages.index'));
    }

    /**
     * Display the specified FarmedTypeStage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $farmedTypeStage = $this->farmedTypeStageRepository->find($id);

        if (empty($farmedTypeStage)) {
            Flash::error('Farmed Type Stage not found');

            return redirect(route('farmedTypeStages.index'));
        }

        return view('farmed_type_stages.show')->with('farmedTypeStage', $farmedTypeStage);
    }

    /**
     * Show the form for editing the specified FarmedTypeStage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $farmedTypeStage = $this->farmedTypeStageRepository->find($id);

        if (empty($farmedTypeStage)) {
            Flash::error('Farmed Type Stage not found');

            return redirect(route('farmedTypeStages.index'));
        }

        return view('farmed_type_stages.edit')->with('farmedTypeStage', $farmedTypeStage);
    }

    /**
     * Update the specified FarmedTypeStage in storage.
     *
     * @param int $id
     * @param UpdateFarmedTypeStageRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFarmedTypeStageRequest $request)
    {
        $farmedTypeStage = $this->farmedTypeStageRepository->find($id);

        if (empty($farmedTypeStage)) {
            Flash::error('Farmed Type Stage not found');

            return redirect(route('farmedTypeStages.index'));
        }

        $farmedTypeStage = $this->farmedTypeStageRepository->update($request->all(), $id);

        Flash::success('Farmed Type Stage updated successfully.');

        return redirect(route('farmedTypeStages.index'));
    }

    /**
     * Remove the specified FarmedTypeStage from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $farmedTypeStage = $this->farmedTypeStageRepository->find($id);

        if (empty($farmedTypeStage)) {
            Flash::error('Farmed Type Stage not found');

            return redirect(route('farmedTypeStages.index'));
        }

        $this->farmedTypeStageRepository->delete($id);

        Flash::success('Farmed Type Stage deleted successfully.');

        return redirect(route('farmedTypeStages.index'));
    }
}
