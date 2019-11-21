<?php

namespace App\Http\Requests;

use Gate;
// use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreClientRequestApi
 * @package App\Http\Requests
 */
class StoreClientRequestApi extends FormRequest
{
    /**
     * @var string
     */
    public $api_token;

    /**
     * @return bool
     */
    public function authorize()
    {
        // abort_if(Gate::denies('client_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'first_name'    => [
                'required',
            ],
            'last_name'     => [
                'required',
            ],
            'email'         => [
                'required',
                'unique:clients',
            ],
            'age'           => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'date_of_birth' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'info'          => [
                'required',
            ],
            'password'      => [
                'required',
            ],
            'phone'         => [
                'required',
                'unique:clients',
            ],
            'address'       => [
                'required',
            ],
        ];
    }

}
