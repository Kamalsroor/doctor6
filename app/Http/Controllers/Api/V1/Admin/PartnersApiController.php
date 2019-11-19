<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StorePartnerRequestApi;
use App\Http\Requests\UpdatePartnerRequest;
use App\Http\Resources\Admin\PartnerResource;
use App\Partner;
use App\Specialty;
use App\Clinic;
use App\Medical;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PartnersApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        return new PartnerResource(Partner::with(['specialty'])->get());
    }

    public function store(StorePartnerRequestApi $request)
    {
        // $partner = Partner::create($request->all());
        $partner = Partner::create([
            'name' => $request->name ,
            'phone' => $request->phone ,
            'username' => $request->username ,
            'password' => $request->password ,
            'type' => $request->type ,
            'specialty_id' => $request->specialty_id ,
        ]);
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
        $partner['Token'] = $partner->createToken('partnerToken')->accessToken;

        return (new PartnerResource($partner))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Partner $partner)
    {
        abort_if(Gate::denies('partner_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PartnerResource($partner->load(['specialty']));
    }

    public function update(UpdatePartnerRequest $request, Partner $partner)
    {
        $partner->update($request->all());

        if ($request->input('avatar', false)) {
            if (!$partner->avatar || $request->input('avatar') !== $partner->avatar->file_name) {
                $partner->addMedia(storage_path('tmp/uploads/' . $request->input('avatar')))->toMediaCollection('avatar');
            }
        } elseif ($partner->avatar) {
            $partner->avatar->delete();
        }

        return (new PartnerResource($partner))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Partner $partner)
    {
        abort_if(Gate::denies('partner_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $partner->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
