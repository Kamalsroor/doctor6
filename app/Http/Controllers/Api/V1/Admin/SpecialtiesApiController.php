<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSpecialtyRequest;
use App\Http\Requests\UpdateSpecialtyRequest;
use App\Http\Resources\Admin\SpecialtyResource;
use App\Specialty;
use Gate;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SpecialtiesApiController
 * @package App\Http\Controllers\Api\V1\Admin
 */
class SpecialtiesApiController extends Controller
{
    /**
     * @return SpecialtyResource
     */
    public function index()
    {
        // abort_if(Gate::denies('specialty_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SpecialtyResource(Specialty::all());
    }

    /**
     * @param StoreSpecialtyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreSpecialtyRequest $request)
    {
        $specialty = Specialty::create($request->all());

        return (new SpecialtyResource($specialty))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @param Specialty $specialty
     * @return SpecialtyResource
     */
    public function show(Specialty $specialty)
    {
        // abort_if(Gate::denies('specialty_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return new SpecialtyResource($specialty->load(['partners']));
    }

    /**
     * @param UpdateSpecialtyRequest $request
     * @param Specialty $specialty
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateSpecialtyRequest $request, Specialty $specialty)
    {
        $specialty->update($request->all());

        return (new SpecialtyResource($specialty))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    /**
     * @param Specialty $specialty
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Specialty $specialty)
    {
        abort_if(Gate::denies('specialty_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $specialty->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
