<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Client;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StorePartnerRequestApi;
use App\Http\Requests\UpdatePartnerRequest;
use App\Http\Resources\Admin\ClientResource;
use App\Http\Resources\Admin\PartnerResource;
use App\Partner;
use App\Clinic;
use App\Medical;
use Gate;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PartnersApiController
 * @package App\Http\Controllers\Api\V1\Admin
 */
class PartnersApiController extends Controller
{
    use MediaUploadingTrait;

    /**
     * @return PartnerResource
     */
    public function index()
    {
        return new PartnerResource(Partner::with(['specialty'])->get());
    }

    /**
     * Undocumented function
     *
     * @param StorePartnerRequestApi $request
     * @return JsonResponse
     */
    public function store(StorePartnerRequestApi $request)
    {
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
        $partner->api_token = Str::random(60);
        $partner->save();
        return (new PartnerResource($partner))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }


    /**
     * @param Request $Request
     * @return JsonResponse
     */
    public function login(Request $Request){

        if (isset($Request->username)) {
            $Partner  = Partner::where('username' , $Request->username)->get();
        }else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
        if(count($Partner) > 0 && count($Partner) < 2 ){
            if (isset($Request->password)) {
                if( ! Hash::check( $Partner[0]->password , $Request->password ) ){
                    $Partner = $Partner[0];
                    return (new ClientResource($Partner))
                        ->response()
                        ->setStatusCode(Response::HTTP_CREATED);
                }else{
                    return response()->json(['error'=>'Unauthorised'], 401);
                }
            }
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }
    /**
     * @param Partner $partner
     * @return PartnerResource
     */
    public function show(Partner $partner)
    {
        abort_if(Gate::denies('partner_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PartnerResource($partner->load(['specialty']));
    }

    /**
     * @param UpdatePartnerRequest $request
     * @param Partner $partner
     * @return JsonResponse
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\DiskDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileIsTooBig
     */
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

    /**
     * @param Partner $partner
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Partner $partner)
    {
        abort_if(Gate::denies('partner_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $partner->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
