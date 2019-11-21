<?php

namespace App\Http\Requests;

use Gate;
// use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StorePartnerRequestApi
 * @package App\Http\Requests
 */
class StorePartnerRequestApi extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        // abort_if(Gate::denies('partner_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    /**
     * @return array
     */
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
