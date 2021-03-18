<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateHomePlantPotSizeRequest;
use App\Http\Requests\UpdateHomePlantPotSizeRequest;
use App\Repositories\HomePlantPotSizeRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class HomePlantPotSizeController extends AppBaseController
{
    /** @var  HomePlantPotSizeRepository */
    private $homePlantPotSizeRepository;

    public function __construct(HomePlantPotSizeRepository $homePlantPotSizeRepo)
    {
        $this->homePlantPotSizeRepository = $homePlantPotSizeRepo;
    }

    /**
     * Display a listing of the HomePlantPotSize.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $homePlantPotSizes = $this->homePlantPotSizeRepository->all();

        return view('home_plant_pot_sizes.index')
            ->with('homePlantPotSizes', $homePlantPotSizes);
    }

    /**
     * Show the form for creating a new HomePlantPotSize.
     *
     * @return Response
     */
    public function create()
    {
        return view('home_plant_pot_sizes.create');
    }

    /**
     * Store a newly created HomePlantPotSize in storage.
     *
     * @param CreateHomePlantPotSizeRequest $request
     *
     * @return Response
     */
    public function store(CreateHomePlantPotSizeRequest $request)
    {
        $input = $request->all();

        $homePlantPotSize = $this->homePlantPotSizeRepository->create($input);

        Flash::success('Home Plant Pot Size saved successfully.');

        return redirect(route('homePlantPotSizes.index'));
    }

    /**
     * Display the specified HomePlantPotSize.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $homePlantPotSize = $this->homePlantPotSizeRepository->find($id);

        if (empty($homePlantPotSize)) {
            Flash::error('Home Plant Pot Size not found');

            return redirect(route('homePlantPotSizes.index'));
        }

        return view('home_plant_pot_sizes.show')->with('homePlantPotSize', $homePlantPotSize);
    }

    /**
     * Show the form for editing the specified HomePlantPotSize.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $homePlantPotSize = $this->homePlantPotSizeRepository->find($id);

        if (empty($homePlantPotSize)) {
            Flash::error('Home Plant Pot Size not found');

            return redirect(route('homePlantPotSizes.index'));
        }

        return view('home_plant_pot_sizes.edit')->with('homePlantPotSize', $homePlantPotSize);
    }

    /**
     * Update the specified HomePlantPotSize in storage.
     *
     * @param int $id
     * @param UpdateHomePlantPotSizeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateHomePlantPotSizeRequest $request)
    {
        $homePlantPotSize = $this->homePlantPotSizeRepository->find($id);

        if (empty($homePlantPotSize)) {
            Flash::error('Home Plant Pot Size not found');

            return redirect(route('homePlantPotSizes.index'));
        }

        $homePlantPotSize = $this->homePlantPotSizeRepository->update($request->all(), $id);

        Flash::success('Home Plant Pot Size updated successfully.');

        return redirect(route('homePlantPotSizes.index'));
    }

    /**
     * Remove the specified HomePlantPotSize from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $homePlantPotSize = $this->homePlantPotSizeRepository->find($id);

        if (empty($homePlantPotSize)) {
            Flash::error('Home Plant Pot Size not found');

            return redirect(route('homePlantPotSizes.index'));
        }

        $this->homePlantPotSizeRepository->delete($id);

        Flash::success('Home Plant Pot Size deleted successfully.');

        return redirect(route('homePlantPotSizes.index'));
    }
}
