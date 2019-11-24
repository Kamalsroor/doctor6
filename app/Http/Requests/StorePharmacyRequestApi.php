<?php

namespace App\Http\Requests;

use Gate;
// use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StorePartnerRequestApi
 * @package App\Http\Requests
 */
class StorePharmacyRequestApi extends FormRequest
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
            'file'    => [
                'required',
            ],
            'phone'     => [
                'required',
            ],
            'client_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
