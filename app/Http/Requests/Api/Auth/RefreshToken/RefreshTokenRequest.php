<?php

namespace App\Http\Requests\Api\Auth\RefreshToken;

use App\Actions\DevelopmentValidator;
use Illuminate\Foundation\Http\FormRequest;

class RefreshTokenRequest extends FormRequest
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
            'refresh_token' => ['nullable', 'string'],
            'phone_number' => ['required'],
            'device_id' => ['required', 'string'],
        ]);
        return [];
    }
}
