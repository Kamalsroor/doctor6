<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UpdatePartnerRequest
 * @package App\Http\Requests
 */
class UpdatePartnerRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        abort_if(Gate::denies('partner_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
            'phone'    => [
                'required',
            ],
            'username' => [
                'required',
            ],
        ];
    }
}
