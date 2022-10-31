<?php

namespace App\Http\Requests\Api\Auth\Login;

use Illuminate\Foundation\Http\FormRequest;
use App\Actions\DevlopmentValidator;

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
        (new DevlopmentValidator)->handle([
            'browser_id' => ['required', 'string'],
        ]);
        return [
            'phone_number' => [
                'required',
            ],
        ];
    }
}
