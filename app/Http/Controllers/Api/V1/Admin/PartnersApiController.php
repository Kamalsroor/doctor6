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
use App\Nurse;
use App\Medical;
use App\Workday;
use App\Worktime;
use Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

use Exception;
use Gate;
use Hash;
use Illuminate\Contracts\Routing\ResponseFactory;
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
        // abort_if(Gate::denies('partner_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PartnerResource($partner->load(['specialty','WorkDay' ,'WorkDay.WorkTimes']));
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
     * @return ResponseFactory|\Illuminate\Http\Response
     * @throws Exception
     */
    public function destroy(Partner $partner)
    {
        abort_if(Gate::denies('partner_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $partner->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function Workday(Request $request)
    {
        $Workday = Workday::where('day' , $request->day )->get();
        if ($Workday->count() > 0){
          return response()->json(['error' => 'تم تسجيل اليوم من قبل'], 404);
        }
        $waiting_time = Auth::guard('partner')->user()->Clinic->waiting_time;
        $Clinic = Carbon::createFromFormat('H:i:s', $waiting_time);
        if ($Clinic->toArray()['hour'] <= 0){
            $waiting_time = null;
            if ($Clinic->toArray()['minute'] > 0 && $Clinic->toArray()['hour'] == 0){
                $waiting_time = $Clinic->toArray()['minute'] .' minute';
            }
        }elseif($Clinic->toArray()['hour'] == 1 ){
            $waiting_time = $Clinic->toArray()['hour'].' hour';
            if ($Clinic->toArray()['minute'] > 0 ){
                $waiting_time = $Clinic->toArray()['hour'].' hour ' . $Clinic->toArray()['minute'] .' minute';
            }
        }
        $period = CarbonPeriod::create($request->from, $waiting_time, $request->to);

        $Workday = Workday::create([
            'partner_id' => Auth::guard('partner')->user()->id ,
            'day' => $request->day ,
            'from' => $request->from ,
            'to' => $request->to ,
            'count' => 1 ,
        ]);
        foreach ($period as $date) {
            $Worktime = new Worktime([
                'time' => $date->format('H:i:s') ,
                'status' => "active"
            ]);
            $Workday->WorkTimes()->save($Worktime);
        }
        return (new PartnerResource($Workday))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @param Partner $partner
     * @return ResponseFactory|\Illuminate\Http\Response
     * @throws Exception
     */
    public function NotActive($id)
    {
        $Worktime = Worktime::findOrFail($id);
        if ($Worktime->status == "active"){
            $Worktime->status= "not-active";
            $Worktime->save();
            return response()->json(['success' => 'تم الغاء الميعاد'], 200);
        }elseif($Worktime->status == "not-active") {
            $Worktime->status= "active";
            $Worktime->save();
            return response()->json(['success' => 'تم تفعيل الميعاد'], 200);
        }

    }

    /**
     * @return PartnerResource
     */
    public function WorkDays()
    {
        return new PartnerResource(Workday::with(['WorkTimes'])->get());
    }

    /**
     * @param $id
     * @return PartnerResource
     */
    public function WorkDayTime($id)
    {
        $Workday = Workday::find($id);
        if ($Workday == Null){
            return response()->json(['error' => 'لا يوجد داتا'], 404);
        }
        return new PartnerResource($Workday->with('WorkTimes')->get());
    }

    /**
     * @param $id
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public function WorkDaysClient(Request $request)
    {

        /** @var TYPE_NAME $Worktime */
        $Worktime = Worktime::findOrFail($request->id);
        if ($Worktime->status == "active"){
            if ($Worktime->client_id != null){
                return response()->json(['error' => 'هذا المعاد غير متاح'], 404);
            }
            $Worktime->client_id= Auth::guard('client')->user()->id;
            $Worktime->status = "booked";
            $Worktime->save();
            return response()->json(['success' => 'تم حجز المعاد بنجاح'], 200);
        }elseif($Worktime->status == "not-active") {
            return response()->json(['error' => 'هذا المعاد غير متاح'], 404);
        }elseif($Worktime->status == "booked"){
            return response()->json(['error' => 'هذا المعاد غير متاح'], 404);
        }elseif($Worktime->status == "bookedDone"){
            return response()->json(['error' => 'هذا المعاد غير متاح'], 404);
        }

    }

    /**
     * @param $id
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public function WorkDaysClientDone(Request $request)
    {

        /** @var TYPE_NAME $Worktime */
        $Worktime = Worktime::findOrFail($request->id);
        if ($Worktime->status == "booked"){
            $Worktime->status = "bookedDone";
            $Worktime->save();
            return response()->json(['success' => 'تم حجز المعاد بنجاح'], 200);
        }elseif($Worktime->status == "not-active") {
            return response()->json(['error' => 'هذا المعاد غير متاح'], 404);
        }elseif($Worktime->status == "active"){
            return response()->json(['error' => 'هذا المعاد غير متاح'], 404);
        }elseif($Worktime->status == "bookedDone"){
            return response()->json(['error' => 'هذا المعاد غير متاح'], 404);
        }

    }


}
