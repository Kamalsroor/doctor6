<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UpdatePharmacyRequest
 * @package App\Http\Requests
 */
class UpdatePharmacyRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        abort_if(Gate::denies('pharmacy_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
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
