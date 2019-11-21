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
