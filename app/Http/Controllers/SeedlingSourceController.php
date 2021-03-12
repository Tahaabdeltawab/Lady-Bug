<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSeedlingSourceRequest;
use App\Http\Requests\UpdateSeedlingSourceRequest;
use App\Repositories\SeedlingSourceRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class SeedlingSourceController extends AppBaseController
{
    /** @var  SeedlingSourceRepository */
    private $seedlingSourceRepository;

    public function __construct(SeedlingSourceRepository $seedlingSourceRepo)
    {
        $this->seedlingSourceRepository = $seedlingSourceRepo;
    }

    /**
     * Display a listing of the SeedlingSource.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $seedlingSources = $this->seedlingSourceRepository->all();

        return view('seedling_sources.index')
            ->with('seedlingSources', $seedlingSources);
    }

    /**
     * Show the form for creating a new SeedlingSource.
     *
     * @return Response
     */
    public function create()
    {
        return view('seedling_sources.create');
    }

    /**
     * Store a newly created SeedlingSource in storage.
     *
     * @param CreateSeedlingSourceRequest $request
     *
     * @return Response
     */
    public function store(CreateSeedlingSourceRequest $request)
    {
        $input = $request->all();

        $seedlingSource = $this->seedlingSourceRepository->create($input);

        Flash::success('Seedling Source saved successfully.');

        return redirect(route('seedlingSources.index'));
    }

    /**
     * Display the specified SeedlingSource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $seedlingSource = $this->seedlingSourceRepository->find($id);

        if (empty($seedlingSource)) {
            Flash::error('Seedling Source not found');

            return redirect(route('seedlingSources.index'));
        }

        return view('seedling_sources.show')->with('seedlingSource', $seedlingSource);
    }

    /**
     * Show the form for editing the specified SeedlingSource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $seedlingSource = $this->seedlingSourceRepository->find($id);

        if (empty($seedlingSource)) {
            Flash::error('Seedling Source not found');

            return redirect(route('seedlingSources.index'));
        }

        return view('seedling_sources.edit')->with('seedlingSource', $seedlingSource);
    }

    /**
     * Update the specified SeedlingSource in storage.
     *
     * @param int $id
     * @param UpdateSeedlingSourceRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSeedlingSourceRequest $request)
    {
        $seedlingSource = $this->seedlingSourceRepository->find($id);

        if (empty($seedlingSource)) {
            Flash::error('Seedling Source not found');

            return redirect(route('seedlingSources.index'));
        }

        $seedlingSource = $this->seedlingSourceRepository->update($request->all(), $id);

        Flash::success('Seedling Source updated successfully.');

        return redirect(route('seedlingSources.index'));
    }

    /**
     * Remove the specified SeedlingSource from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $seedlingSource = $this->seedlingSourceRepository->find($id);

        if (empty($seedlingSource)) {
            Flash::error('Seedling Source not found');

            return redirect(route('seedlingSources.index'));
        }

        $this->seedlingSourceRepository->delete($id);

        Flash::success('Seedling Source deleted successfully.');

        return redirect(route('seedlingSources.index'));
    }
}
