<?php

namespace App\Http\Requests\Api\Auth\Login;

use Illuminate\Foundation\Http\FormRequest;
use App\Actions\DevelopmentValidator;

class LoginRequest extends FormRequest
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
        (new DevelopmentValidator())->handle([
            'device_id' => ['required', 'string'],
            'noti_token' => ['string'],
            'language' => ['required', 'string'],
        ]);
        return [
            'phone_number' => ['required'],
            'password' => ['required', 'string'],
        ];
    }
}
