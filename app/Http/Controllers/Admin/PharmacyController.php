<?php

namespace App\Http\Controllers\Admin;

use App\Client;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyPharmacyRequest;
use App\Http\Requests\StorePharmacyRequest;
use App\Http\Requests\UpdatePharmacyRequest;
use App\Pharmacy;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PharmacyController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Pharmacy::with(['client'])->select(sprintf('%s.*', (new Pharmacy)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'pharmacy_show';
                $editGate      = 'pharmacy_edit';
                $deleteGate    = 'pharmacy_delete';
                $crudRoutePart = 'pharmacies';

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
            $table->editColumn('file', function ($row) {
                if (!$row->file) {
                    return '';
                }

                $links = [];

                foreach ($row->file as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->editColumn('phone', function ($row) {
                return $row->phone ? $row->phone : "";
            });
            $table->addColumn('client_email', function ($row) {
                return $row->client ? $row->client->email : '';
            });

            $table->editColumn('client.first_name', function ($row) {
                return $row->client ? (is_string($row->client) ? $row->client : $row->client->first_name) : '';
            });
            $table->editColumn('client.last_name', function ($row) {
                return $row->client ? (is_string($row->client) ? $row->client : $row->client->last_name) : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'file', 'client']);

            return $table->make(true);
        }

        return view('admin.pharmacies.index');
    }

    public function create()
    {
        abort_if(Gate::denies('pharmacy_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clients = Client::all()->pluck('email', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.pharmacies.create', compact('clients'));
    }

    public function store(StorePharmacyRequest $request)
    {
        $pharmacy = Pharmacy::create($request->all());

        foreach ($request->input('file', []) as $file) {
            $pharmacy->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file');
        }

        return redirect()->route('admin.pharmacies.index');
    }

    public function edit(Pharmacy $pharmacy)
    {
        abort_if(Gate::denies('pharmacy_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clients = Client::all()->pluck('email', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pharmacy->load('client');

        return view('admin.pharmacies.edit', compact('clients', 'pharmacy'));
    }

    public function update(UpdatePharmacyRequest $request, Pharmacy $pharmacy)
    {
        $pharmacy->update($request->all());

        if (count($pharmacy->file) > 0) {
            foreach ($pharmacy->file as $media) {
                if (!in_array($media->file_name, $request->input('file', []))) {
                    $media->delete();
                }
            }
        }

        $media = $pharmacy->file->pluck('file_name')->toArray();

        foreach ($request->input('file', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $pharmacy->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file');
            }
        }

        return redirect()->route('admin.pharmacies.index');
    }

    public function show(Pharmacy $pharmacy)
    {
        abort_if(Gate::denies('pharmacy_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pharmacy->load('client');

        return view('admin.pharmacies.show', compact('pharmacy'));
    }

    public function destroy(Pharmacy $pharmacy)
    {
        abort_if(Gate::denies('pharmacy_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pharmacy->delete();

        return back();
    }

    public function massDestroy(MassDestroyPharmacyRequest $request)
    {
        Pharmacy::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
