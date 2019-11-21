<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MassDestroyPartnerRequest
 * @package App\Http\Requests
 */
class MassDestroyPartnerRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        abort_if(Gate::denies('partner_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:partners,id',
        ];
    }
}
