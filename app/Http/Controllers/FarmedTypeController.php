<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFarmedTypeRequest;
use App\Http\Requests\UpdateFarmedTypeRequest;
use App\Repositories\FarmedTypeRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class FarmedTypeController extends AppBaseController
{
    /** @var  FarmedTypeRepository */
    private $farmedTypeRepository;

    public function __construct(FarmedTypeRepository $farmedTypeRepo)
    {
        $this->farmedTypeRepository = $farmedTypeRepo;
    }

    /**
     * Display a listing of the FarmedType.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $farmedTypes = $this->farmedTypeRepository->all();

        return view('farmed_types.index')
            ->with('farmedTypes', $farmedTypes);
    }

    /**
     * Show the form for creating a new FarmedType.
     *
     * @return Response
     */
    public function create()
    {
        return view('farmed_types.create');
    }

    /**
     * Store a newly created FarmedType in storage.
     *
     * @param CreateFarmedTypeRequest $request
     *
     * @return Response
     */
    public function store(CreateFarmedTypeRequest $request)
    {
        $input = $request->all();

        $farmedType = $this->farmedTypeRepository->create($input);

        Flash::success('Farmed Type saved successfully.');

        return redirect(route('farmedTypes.index'));
    }

    /**
     * Display the specified FarmedType.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $farmedType = $this->farmedTypeRepository->find($id);

        if (empty($farmedType)) {
            Flash::error('Farmed Type not found');

            return redirect(route('farmedTypes.index'));
        }

        return view('farmed_types.show')->with('farmedType', $farmedType);
    }

    /**
     * Show the form for editing the specified FarmedType.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $farmedType = $this->farmedTypeRepository->find($id);

        if (empty($farmedType)) {
            Flash::error('Farmed Type not found');

            return redirect(route('farmedTypes.index'));
        }

        return view('farmed_types.edit')->with('farmedType', $farmedType);
    }

    /**
     * Update the specified FarmedType in storage.
     *
     * @param int $id
     * @param UpdateFarmedTypeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFarmedTypeRequest $request)
    {
        $farmedType = $this->farmedTypeRepository->find($id);

        if (empty($farmedType)) {
            Flash::error('Farmed Type not found');

            return redirect(route('farmedTypes.index'));
        }

        $farmedType = $this->farmedTypeRepository->update($request->all(), $id);

        Flash::success('Farmed Type updated successfully.');

        return redirect(route('farmedTypes.index'));
    }

    /**
     * Remove the specified FarmedType from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $farmedType = $this->farmedTypeRepository->find($id);

        if (empty($farmedType)) {
            Flash::error('Farmed Type not found');

            return redirect(route('farmedTypes.index'));
        }

        $this->farmedTypeRepository->delete($id);

        Flash::success('Farmed Type deleted successfully.');

        return redirect(route('farmedTypes.index'));
    }
}
