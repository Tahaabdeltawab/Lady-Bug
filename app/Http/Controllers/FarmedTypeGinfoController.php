<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFarmedTypeGinfoRequest;
use App\Http\Requests\UpdateFarmedTypeGinfoRequest;
use App\Repositories\FarmedTypeGinfoRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class FarmedTypeGinfoController extends AppBaseController
{
    /** @var  FarmedTypeGinfoRepository */
    private $farmedTypeGinfoRepository;

    public function __construct(FarmedTypeGinfoRepository $farmedTypeGinfoRepo)
    {
        $this->farmedTypeGinfoRepository = $farmedTypeGinfoRepo;
    }

    /**
     * Display a listing of the FarmedTypeGinfo.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $farmedTypeGinfos = $this->farmedTypeGinfoRepository->all();

        return view('farmed_type_ginfos.index')
            ->with('farmedTypeGinfos', $farmedTypeGinfos);
    }

    /**
     * Show the form for creating a new FarmedTypeGinfo.
     *
     * @return Response
     */
    public function create()
    {
        return view('farmed_type_ginfos.create');
    }

    /**
     * Store a newly created FarmedTypeGinfo in storage.
     *
     * @param CreateFarmedTypeGinfoRequest $request
     *
     * @return Response
     */
    public function store(CreateFarmedTypeGinfoRequest $request)
    {
        $input = $request->all();

        $farmedTypeGinfo = $this->farmedTypeGinfoRepository->create($input);

        Flash::success('Farmed Type Ginfo saved successfully.');

        return redirect(route('farmedTypeGinfos.index'));
    }

    /**
     * Display the specified FarmedTypeGinfo.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $farmedTypeGinfo = $this->farmedTypeGinfoRepository->find($id);

        if (empty($farmedTypeGinfo)) {
            Flash::error('Farmed Type Ginfo not found');

            return redirect(route('farmedTypeGinfos.index'));
        }

        return view('farmed_type_ginfos.show')->with('farmedTypeGinfo', $farmedTypeGinfo);
    }

    /**
     * Show the form for editing the specified FarmedTypeGinfo.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $farmedTypeGinfo = $this->farmedTypeGinfoRepository->find($id);

        if (empty($farmedTypeGinfo)) {
            Flash::error('Farmed Type Ginfo not found');

            return redirect(route('farmedTypeGinfos.index'));
        }

        return view('farmed_type_ginfos.edit')->with('farmedTypeGinfo', $farmedTypeGinfo);
    }

    /**
     * Update the specified FarmedTypeGinfo in storage.
     *
     * @param int $id
     * @param UpdateFarmedTypeGinfoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFarmedTypeGinfoRequest $request)
    {
        $farmedTypeGinfo = $this->farmedTypeGinfoRepository->find($id);

        if (empty($farmedTypeGinfo)) {
            Flash::error('Farmed Type Ginfo not found');

            return redirect(route('farmedTypeGinfos.index'));
        }

        $farmedTypeGinfo = $this->farmedTypeGinfoRepository->update($request->all(), $id);

        Flash::success('Farmed Type Ginfo updated successfully.');

        return redirect(route('farmedTypeGinfos.index'));
    }

    /**
     * Remove the specified FarmedTypeGinfo from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $farmedTypeGinfo = $this->farmedTypeGinfoRepository->find($id);

        if (empty($farmedTypeGinfo)) {
            Flash::error('Farmed Type Ginfo not found');

            return redirect(route('farmedTypeGinfos.index'));
        }

        $this->farmedTypeGinfoRepository->delete($id);

        Flash::success('Farmed Type Ginfo deleted successfully.');

        return redirect(route('farmedTypeGinfos.index'));
    }
}
