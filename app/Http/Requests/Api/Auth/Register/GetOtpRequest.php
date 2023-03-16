<?php

namespace App\Http\Requests\Api\Auth\Register;

use Illuminate\Foundation\Http\FormRequest;
use App\Actions\DevelopmentValidator;

class GetOtpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        (new DevelopmentValidator)->handle([
            'device_id' => ['required', 'string'],
        ]);

        return [
            'phone_number' => [
                'required',
                'unique:users',
                'string',
                'regex:/^((09)|(\+959)|())((2[5-7])|(4[0-5])|(7[6-9])|(9[6-9])|(7[7-9])|(6[6-9])|(3[1-2]))[0-9]{7}$/'
            ],
        ];
    }
}
