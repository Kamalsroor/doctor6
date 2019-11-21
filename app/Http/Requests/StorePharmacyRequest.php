<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class StorePharmacyRequest
 * @package App\Http\Requests
 */
class StorePharmacyRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        abort_if(Gate::denies('pharmacy_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'file.*'    => [
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
