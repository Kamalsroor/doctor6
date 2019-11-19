<?php

namespace App\Http\Requests;

use App\Client;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateClientRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('client_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

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
                'unique:clients,email,' . request()->route('client')->id,
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
            'phone'         => [
                'required',
                'unique:clients,phone,' . request()->route('client')->id,
            ],
            'address'       => [
                'required',
            ],
        ];
    }
}
