<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class StorePartnerRequest
 * @package App\Http\Requests
 */
class StorePartnerRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        abort_if(Gate::denies('partner_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
