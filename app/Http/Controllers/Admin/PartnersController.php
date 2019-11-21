<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyPartnerRequest;
use App\Http\Requests\StorePartnerRequest;
use App\Http\Requests\UpdatePartnerRequest;
use App\Partner;
use App\Specialty;
use App\Clinic;
use App\Medical;
use App\Nurse;

use Exception;
use Gate;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class PartnersController
 * @package App\Http\Controllers\Admin
 */
class PartnersController extends Controller
{
    use MediaUploadingTrait;

    /**
     * @param Request $request
     * @return Factory|View
     * @throws Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Partner::with(['specialty'])->select(sprintf('%s.*', (new Partner)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'partner_show';
                $editGate      = 'partner_edit';
                $deleteGate    = 'partner_delete';
                $crudRoutePart = 'partners';

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
            $table->editColumn('avatar', function ($row) {
                if ($photo = $row->avatar) {
                    return sprintf(
                        '<a href="%s" target="_blank"><img src="%s" width="50px" height="50px"></a>',
                        $photo->url,
                        $photo->thumbnail
                    );
                }

                return '';
            });
            $table->editColumn('phone', function ($row) {
                return $row->phone ? $row->phone : "";
            });
            $table->editColumn('username', function ($row) {
                return $row->username ? $row->username : "";
            });
            $table->editColumn('type', function ($row) {
                return $row->type ? Partner::TYPE_SELECT[$row->type] : '';
            });
            $table->addColumn('specialty_name', function ($row) {
                return $row->specialty ? $row->specialty->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'avatar', 'specialty']);

            return $table->make(true);
        }

        return view('admin.partners.index');
    }

    /**
     * @return Factory|View
     */
    public function create()
    {
        abort_if(Gate::denies('partner_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $specialties = Specialty::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.partners.create', compact('specialties'));
    }

    /**
     * @param StorePartnerRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StorePartnerRequest $request)
    {
        // dd($request);
        $partner = Partner::create([
            'name' => $request->name ,
            'phone' => $request->phone ,
            'username' => $request->username ,
            'password' => $request->password ,
            'type' => $request->type ,
            'specialty_id' => $request->specialty_id ,
        ]);
        // $partner->Clinic->create();
        if ($request->input('avatar', false)) {
            $partner->addMedia(storage_path('tmp/uploads/' . $request->input('avatar')))->toMediaCollection('avatar');
        }
        if( $request->type == "clinic"){
            $Clinic = new Clinic([
                'price' => $request->price ,
                'address' => $request->address ,
                'waiting_time' => $request->waiting_time ,
                'info' => $request->info ,
                'long' => $request->long ,
                'lat' => $request->lat ,
            ]);
            $partner->Clinic()->save($Clinic);
        }elseif($request->type == "medical"){
            $Medical = new Medical([
                'address' => $request->address ,
                'waiting_time' => $request->waiting_time ,
                'info' => $request->info ,
                'long' => $request->long ,
                'lat' => $request->lat ,
            ]);

            $partner->Medical()->save($Medical);
        }elseif($request->type == "nurse"){
            $Nurse = new Nurse([
                'experience' => $request->experience ,
                'age' => $request->age ,
            ]);
            $partner->Nurse()->save($Nurse);
        }
            return redirect()->route('admin.partners.index');
    }

    /**
     * @param Partner $partner
     * @return Factory|View
     */
    public function edit(Partner $partner)
    {
        abort_if(Gate::denies('partner_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $specialties = Specialty::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $partner->load('specialty');

        return view('admin.partners.edit', compact('specialties', 'partner'));
    }

    /**
     * @param UpdatePartnerRequest $request
     * @param Partner $partner
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\DiskDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileIsTooBig
     */
    public function update(UpdatePartnerRequest $request, Partner $partner)
    {
        if ( $request->password == null) {
            $request->password = $partner->password;
        }
        $partner->update([
            'name' => $request->name ,
            'phone' => $request->phone ,
            'username' => $request->username ,
            'password' => $request->password ,
            'type' => $request->type ,
            'specialty_id' => $request->specialty_id ,
        ]);

        if ($request->input('avatar', false)) {
            if (!$partner->avatar || $request->input('avatar') !== $partner->avatar->file_name) {
                $partner->addMedia(storage_path('tmp/uploads/' . $request->input('avatar')))->toMediaCollection('avatar');
            }
        } elseif ($partner->avatar) {
            $partner->avatar->delete();
        }
        if( $request->type == "clinic"){
            $partner->Clinic()->update([
                'price' => $request->price ,
                'address' => $request->address ,
                'waiting_time' => $request->waiting_time ,
                'info' => $request->info ,
                'long' => $request->long ,
                'lat' => $request->lat ,
            ]);
        }elseif($request->type == "medical"){
            $partner->Medical()->update([
                'address' => $request->address ,
                'waiting_time' => $request->waiting_time ,
                'info' => $request->info ,
                'long' => $request->long ,
                'lat' => $request->lat ,
            ]);
        }elseif($request->type == "nurse"){
            $partner->Nurse()->update([
                'experience' => $request->experience ,
                'age' => $request->age ,
            ]);
        }

        return redirect()->route('admin.partners.index');
    }

    /**
     * @param Partner $partner
     * @return Factory|View
     */
    public function show(Partner $partner)
    {
        abort_if(Gate::denies('partner_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $partner->load('specialty');

        return view('admin.partners.show', compact('partner'));
    }

    /**
     * @param Partner $partner
     * @return \Illuminate\Http\RedirectResponse
     * @throws Exception
     */
    public function destroy(Partner $partner)
    {
        abort_if(Gate::denies('partner_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $partner->delete();

        return back();
    }

    /**
     * @param MassDestroyPartnerRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function massDestroy(MassDestroyPartnerRequest $request)
    {
        Partner::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
