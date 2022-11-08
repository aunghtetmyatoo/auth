<?php

namespace App\Http\Requests\Api\Auth\Register;

use App\Actions\DevelopmentValidator;
use App\Constants\MigrationLength;
use Illuminate\Foundation\Http\FormRequest;

class PlayerRegisterRequest extends FormRequest
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
            'noti_token' => ['required', 'string', 'max:' . MigrationLength::NOTI_TOKEN],
            'phone_number' => [
                'required',
                'unique:users',
                'unique:admins',
            ],
            'name' => ['required', 'string', 'min:2', 'max:100'],
        ]);

        return [
            'password' => ['required', 'min:6'],
        ];
    }
}
