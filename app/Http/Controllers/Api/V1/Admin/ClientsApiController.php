<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Client;
use Str;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreClientRequestApi;
use App\Http\Requests\UpdateClientRequest;
use App\Http\Resources\Admin\ClientResource;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Hash;
class ClientsApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        // abort_if(Gate::denies('client_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return new ClientResource(Client::all());
    }
    /*
    * Undocumented function
    *
    * @return void
    */
    public function login(Request $Request){ 
        $Client  = Client::where('email' , $Request->email)->get();
        if(count($Client) > 0 && count($Client) < 2 ){ 
            // dd($Client[0]->password);
            if( ! Hash::check( $Client[0]->password , $Request->password ) ){
                $Client = $Client[0];
                // $Client['token'] =  $Client->createToken('clientToken')->accessToken; 
                return (new ClientResource($Client))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
            }else{ 
                return response()->json(['error'=>'Unauthorised'], 401); 
            } 
        } 
        else{ 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } 
    }

    
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

    public function show(Client $client)
    {
        abort_if(Gate::denies('client_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ClientResource($client);
    }

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

        return (new ClientResource($client))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Client $client)
    {
        abort_if(Gate::denies('client_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $client->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
