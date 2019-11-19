<?php

namespace App\Http\Requests;

use App\Partner;
use Gate;
// use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\FormRequest;

class StorePartnerRequestApi extends FormRequest
{
    public function authorize()
    {
        // abort_if(Gate::denies('partner_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name'     => [
                'required',
            ],
            'avatar'   => [
                'required',
            ],
            'phone'    => [
                'required',
            ],
            'username' => [
                'required',
            ],
            'password' => [
                'required',
            ],
        ];
    }
}