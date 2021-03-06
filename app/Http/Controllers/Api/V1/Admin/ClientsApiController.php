<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Client;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\DiskDoesNotExist;
use Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileDoesNotExist;
use Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileIsTooBig;
use Str;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreClientRequestApi;
use App\Http\Requests\UpdateClientRequestApi;
use App\Http\Resources\Admin\ClientResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Hash;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ClientsApiController
 * @package App\Http\Controllers\Api\V1\Admin
 */
class ClientsApiController extends Controller
{
    use MediaUploadingTrait;


    /**
     * @return ClientResource
     */
    public function index()
    {
        return new ClientResource(Client::get());
    }



    /**
     * @param Request $Request
     * @return JsonResponse
     */
    public function login(Request $Request){
        if (isset($Request->email)) {
            $Client  = Client::where('email' , $Request->email)->get();
        }
        if(count($Client) > 0 && count($Client) < 2 ){
            if (isset($Request->password)) {
                if( ! Hash::check( $Client[0]->password , $Request->password ) ){
                    $Client = $Client[0];
                    return (new ClientResource($Client))
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
     * @param StoreClientRequestApi $request
     * @return JsonResponse
     * @throws DiskDoesNotExist
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function store(StoreClientRequestApi $request)
    {
        $request->api_token = Str::random(60);
        // dd($request->api_token);
        $client = Client::create($request->all());
        if ($request->input('avatar', false)) {
            $client->addMedia(storage_path('tmp/uploads/' . $request->input('avatar')))->toMediaCollection('avatar');
        }
        $client->api_token = $request->api_token;
        $client->save();
        return (new ClientResource($client))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @param Client $client
     * @return ClientResource
     */
    public function show(Client $client)
    {
        return new ClientResource($client);
    }

    /**
     * @param UpdateClientRequestApi $request
     * @param Client $client
     * @return JsonResponse
     * @throws DiskDoesNotExist
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function update(UpdateClientRequestApi $request, Client $client)
    {
        // dd('test');
        if (!empty($request)) {
            $client->update($request->all());
        }

        if ($request->input('avatar', false)) {
            if (!$client->avatar || $request->input('avatar') !== $client->avatar->file_name) {
                $client->addMedia(storage_path('tmp/uploads/' . $request->input('avatar')))->toMediaCollection('avatar');
            }
        } elseif ($client->avatar) {
            $client->avatar->delete();
        }

        return (new ClientResource($client))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    /**
     * @param Client $client
     * @return ResponseFactory|\Illuminate\Http\Response
     * @throws Exception
     */
    public function destroy(Client $client)
    {
        abort_if(Gate::denies('client_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $client->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
