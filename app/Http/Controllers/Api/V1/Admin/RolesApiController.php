<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\Admin\RoleResource;
use App\Role;
use Gate;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RolesApiController
 * @package App\Http\Controllers\Api\V1\Admin
 */
class RolesApiController extends Controller
{
    /**
     * @return RoleResource
     */
    public function index()
    {
        abort_if(Gate::denies('role_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RoleResource(Role::with(['permissions'])->get());
    }

    /**
     * @param StoreRoleRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRoleRequest $request)
    {
        $role = Role::create($request->all());
        $role->permissions()->sync($request->input('permissions', []));

        return (new RoleResource($role))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @param Role $role
     * @return RoleResource
     */
    public function show(Role $role)
    {
        abort_if(Gate::denies('role_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RoleResource($role->load(['permissions']));
    }

    /**
     * @param UpdateRoleRequest $request
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $role->update($request->all());
        $role->permissions()->sync($request->input('permissions', []));

        return (new RoleResource($role))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    /**
     * @param Role $role
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Role $role)
    {
        abort_if(Gate::denies('role_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $role->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
