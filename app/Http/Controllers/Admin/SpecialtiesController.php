<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySpecialtyRequest;
use App\Http\Requests\StoreSpecialtyRequest;
use App\Http\Requests\UpdateSpecialtyRequest;
use App\Specialty;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class SpecialtiesController
 * @package App\Http\Controllers\Admin
 */
class SpecialtiesController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Specialty::query()->select(sprintf('%s.*', (new Specialty)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'specialty_show';
                $editGate      = 'specialty_edit';
                $deleteGate    = 'specialty_delete';
                $crudRoutePart = 'specialties';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : "";
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.specialties.index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        abort_if(Gate::denies('specialty_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.specialties.create');
    }

    /**
     * @param StoreSpecialtyRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreSpecialtyRequest $request)
    {
        $specialty = Specialty::create($request->all());

        return redirect()->route('admin.specialties.index');
    }

    /**
     * @param Specialty $specialty
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Specialty $specialty)
    {
        abort_if(Gate::denies('specialty_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.specialties.edit', compact('specialty'));
    }

    /**
     * @param UpdateSpecialtyRequest $request
     * @param Specialty $specialty
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateSpecialtyRequest $request, Specialty $specialty)
    {
        $specialty->update($request->all());

        return redirect()->route('admin.specialties.index');
    }

    /**
     * @param Specialty $specialty
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Specialty $specialty)
    {
        abort_if(Gate::denies('specialty_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.specialties.show', compact('specialty'));
    }

    /**
     * @param Specialty $specialty
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Specialty $specialty)
    {
        abort_if(Gate::denies('specialty_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $specialty->delete();

        return back();
    }

    /**
     * @param MassDestroySpecialtyRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function massDestroy(MassDestroySpecialtyRequest $request)
    {
        Specialty::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
