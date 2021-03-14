<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSaltTypeRequest;
use App\Http\Requests\UpdateSaltTypeRequest;
use App\Repositories\SaltTypeRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class SaltTypeController extends AppBaseController
{
    /** @var  SaltTypeRepository */
    private $saltTypeRepository;

    public function __construct(SaltTypeRepository $saltTypeRepo)
    {
        $this->saltTypeRepository = $saltTypeRepo;
    }

    /**
     * Display a listing of the SaltType.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $saltTypes = $this->saltTypeRepository->all();

        return view('salt_types.index')
            ->with('saltTypes', $saltTypes);
    }

    /**
     * Show the form for creating a new SaltType.
     *
     * @return Response
     */
    public function create()
    {
        return view('salt_types.create');
    }

    /**
     * Store a newly created SaltType in storage.
     *
     * @param CreateSaltTypeRequest $request
     *
     * @return Response
     */
    public function store(CreateSaltTypeRequest $request)
    {
        $input = $request->all();

        $saltType = $this->saltTypeRepository->create($input);

        Flash::success('Salt Type saved successfully.');

        return redirect(route('saltTypes.index'));
    }

    /**
     * Display the specified SaltType.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $saltType = $this->saltTypeRepository->find($id);

        if (empty($saltType)) {
            Flash::error('Salt Type not found');

            return redirect(route('saltTypes.index'));
        }

        return view('salt_types.show')->with('saltType', $saltType);
    }

    /**
     * Show the form for editing the specified SaltType.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $saltType = $this->saltTypeRepository->find($id);

        if (empty($saltType)) {
            Flash::error('Salt Type not found');

            return redirect(route('saltTypes.index'));
        }

        return view('salt_types.edit')->with('saltType', $saltType);
    }

    /**
     * Update the specified SaltType in storage.
     *
     * @param int $id
     * @param UpdateSaltTypeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSaltTypeRequest $request)
    {
        $saltType = $this->saltTypeRepository->find($id);

        if (empty($saltType)) {
            Flash::error('Salt Type not found');

            return redirect(route('saltTypes.index'));
        }

        $saltType = $this->saltTypeRepository->update($request->all(), $id);

        Flash::success('Salt Type updated successfully.');

        return redirect(route('saltTypes.index'));
    }

    /**
     * Remove the specified SaltType from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $saltType = $this->saltTypeRepository->find($id);

        if (empty($saltType)) {
            Flash::error('Salt Type not found');

            return redirect(route('saltTypes.index'));
        }

        $this->saltTypeRepository->delete($id);

        Flash::success('Salt Type deleted successfully.');

        return redirect(route('saltTypes.index'));
    }
}
