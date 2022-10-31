<?php

namespace App\Http\Requests\Api\Auth\Login;

use Illuminate\Foundation\Http\FormRequest;
use App\Actions\DevelopmentValidator;
use App\Constants\AuthConstant;
use App\Constants\MigrationLength;

class VerifyOtpRequest extends FormRequest
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
            'browser_id' => ['required', 'string'],
            'phone_number' => [
                'required',
            ],
        ]);
        return [
            'otp' => ['required', 'numeric', 'digits:6'],
        ];
    }
}
