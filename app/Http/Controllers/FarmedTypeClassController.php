<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFarmedTypeClassRequest;
use App\Http\Requests\UpdateFarmedTypeClassRequest;
use App\Repositories\FarmedTypeClassRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class FarmedTypeClassController extends AppBaseController
{
    /** @var  FarmedTypeClassRepository */
    private $farmedTypeClassRepository;

    public function __construct(FarmedTypeClassRepository $farmedTypeClassRepo)
    {
        $this->farmedTypeClassRepository = $farmedTypeClassRepo;
    }

    /**
     * Display a listing of the FarmedTypeClass.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $farmedTypeClasses = $this->farmedTypeClassRepository->all();

        return view('farmed_type_classes.index')
            ->with('farmedTypeClasses', $farmedTypeClasses);
    }

    /**
     * Show the form for creating a new FarmedTypeClass.
     *
     * @return Response
     */
    public function create()
    {
        return view('farmed_type_classes.create');
    }

    /**
     * Store a newly created FarmedTypeClass in storage.
     *
     * @param CreateFarmedTypeClassRequest $request
     *
     * @return Response
     */
    public function store(CreateFarmedTypeClassRequest $request)
    {
        $input = $request->all();

        $farmedTypeClass = $this->farmedTypeClassRepository->create($input);

        Flash::success('Farmed Type Class saved successfully.');

        return redirect(route('farmedTypeClasses.index'));
    }

    /**
     * Display the specified FarmedTypeClass.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $farmedTypeClass = $this->farmedTypeClassRepository->find($id);

        if (empty($farmedTypeClass)) {
            Flash::error('Farmed Type Class not found');

            return redirect(route('farmedTypeClasses.index'));
        }

        return view('farmed_type_classes.show')->with('farmedTypeClass', $farmedTypeClass);
    }

    /**
     * Show the form for editing the specified FarmedTypeClass.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $farmedTypeClass = $this->farmedTypeClassRepository->find($id);

        if (empty($farmedTypeClass)) {
            Flash::error('Farmed Type Class not found');

            return redirect(route('farmedTypeClasses.index'));
        }

        return view('farmed_type_classes.edit')->with('farmedTypeClass', $farmedTypeClass);
    }

    /**
     * Update the specified FarmedTypeClass in storage.
     *
     * @param int $id
     * @param UpdateFarmedTypeClassRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFarmedTypeClassRequest $request)
    {
        $farmedTypeClass = $this->farmedTypeClassRepository->find($id);

        if (empty($farmedTypeClass)) {
            Flash::error('Farmed Type Class not found');

            return redirect(route('farmedTypeClasses.index'));
        }

        $farmedTypeClass = $this->farmedTypeClassRepository->update($request->all(), $id);

        Flash::success('Farmed Type Class updated successfully.');

        return redirect(route('farmedTypeClasses.index'));
    }

    /**
     * Remove the specified FarmedTypeClass from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $farmedTypeClass = $this->farmedTypeClassRepository->find($id);

        if (empty($farmedTypeClass)) {
            Flash::error('Farmed Type Class not found');

            return redirect(route('farmedTypeClasses.index'));
        }

        $this->farmedTypeClassRepository->delete($id);

        Flash::success('Farmed Type Class deleted successfully.');

        return redirect(route('farmedTypeClasses.index'));
    }
}
