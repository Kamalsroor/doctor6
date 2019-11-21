<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class StorePermissionRequest
 * @package App\Http\Requests
 */
class StorePermissionRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        abort_if(Gate::denies('permission_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'title' => [
                'required',
            ],
        ];
    }
}
