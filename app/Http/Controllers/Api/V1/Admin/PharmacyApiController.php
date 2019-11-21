<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StorePharmacyRequest;
use App\Http\Requests\UpdatePharmacyRequest;
use App\Http\Resources\Admin\PharmacyResource;
use App\Pharmacy;
use Gate;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PharmacyApiController
 * @package App\Http\Controllers\Api\V1\Admin
 */
class PharmacyApiController extends Controller
{
    use MediaUploadingTrait;

    /**
     * @return PharmacyResource
     */
    public function index()
    {
        abort_if(Gate::denies('pharmacy_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PharmacyResource(Pharmacy::with(['client'])->get());
    }

    /**
     * @param StorePharmacyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePharmacyRequest $request)
    {
        $pharmacy = Pharmacy::create($request->all());

        if ($request->input('file', false)) {
            $pharmacy->addMedia(storage_path('tmp/uploads/' . $request->input('file')))->toMediaCollection('file');
        }

        return (new PharmacyResource($pharmacy))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @param Pharmacy $pharmacy
     * @return PharmacyResource
     */
    public function show(Pharmacy $pharmacy)
    {
        abort_if(Gate::denies('pharmacy_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PharmacyResource($pharmacy->load(['client']));
    }

    /**
     * @param UpdatePharmacyRequest $request
     * @param Pharmacy $pharmacy
     * @return \Illuminate\Http\JsonResponse
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\DiskDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileIsTooBig
     */
    public function update(UpdatePharmacyRequest $request, Pharmacy $pharmacy)
    {
        $pharmacy->update($request->all());

        if ($request->input('file', false)) {
            if (!$pharmacy->file || $request->input('file') !== $pharmacy->file->file_name) {
                $pharmacy->addMedia(storage_path('tmp/uploads/' . $request->input('file')))->toMediaCollection('file');
            }
        } elseif ($pharmacy->file) {
            $pharmacy->file->delete();
        }

        return (new PharmacyResource($pharmacy))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    /**
     * @param Pharmacy $pharmacy
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Pharmacy $pharmacy)
    {
        abort_if(Gate::denies('pharmacy_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pharmacy->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
