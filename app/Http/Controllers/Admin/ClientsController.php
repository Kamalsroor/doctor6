<?php

namespace App\Http\Controllers\Admin;

use App\Client;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyClientRequest;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class ClientsController
 * @package App\Http\Controllers\Admin
 */
class ClientsController extends Controller
{
    use MediaUploadingTrait;

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Client::query()->select(sprintf('%s.*', (new Client)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'client_show';
                $editGate      = 'client_edit';
                $deleteGate    = 'client_delete';
                $crudRoutePart = 'clients';

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
            $table->editColumn('first_name', function ($row) {
                return $row->first_name ? $row->first_name : "";
            });
            $table->editColumn('last_name', function ($row) {
                return $row->last_name ? $row->last_name : "";
            });
            $table->editColumn('email', function ($row) {
                return $row->email ? $row->email : "";
            });
            $table->editColumn('age', function ($row) {
                return $row->age ? $row->age : "";
            });
            $table->editColumn('gender', function ($row) {
                return $row->gender ? Client::GENDER_SELECT[$row->gender] : '';
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

            $table->rawColumns(['actions', 'placeholder', 'avatar']);

            return $table->make(true);
        }

        return view('admin.clients.index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        abort_if(Gate::denies('client_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.clients.create');
    }

    /**
     * @param StoreClientRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreClientRequest $request)
    {
        $client = Client::create($request->all());

        if ($request->input('avatar', false)) {
            $client->addMedia(storage_path('tmp/uploads/' . $request->input('avatar')))->toMediaCollection('avatar');
        }

        return redirect()->route('admin.clients.index');
    }

    /**
     * @param Client $client
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Client $client)
    {
        abort_if(Gate::denies('client_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.clients.edit', compact('client'));
    }

    /**
     * @param UpdateClientRequest $request
     * @param Client $client
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\DiskDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileIsTooBig
     */
    public function update(UpdateClientRequest $request, Client $client)
    {
        $client->update($request->all());

        if ($request->input('avatar', false)) {
            if (!$client->avatar || $request->input('avatar') !== $client->avatar->file_name) {
                $client->addMedia(storage_path('tmp/uploads/' . $request->input('avatar')))->toMediaCollection('avatar');
            }
        } elseif ($client->avatar) {
            $client->avatar->delete();
        }

        return redirect()->route('admin.clients.index');
    }

    /**
     * @param Client $client
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Client $client)
    {
        abort_if(Gate::denies('client_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.clients.show', compact('client'));
    }

    /**
     * @param Client $client
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Client $client)
    {
        abort_if(Gate::denies('client_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $client->delete();

        return back();
    }

    /**
     * @param MassDestroyClientRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function massDestroy(MassDestroyClientRequest $request)
    {
        Client::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
