<?php

namespace App\Http\Requests\Api\PlayerSetting;

use Illuminate\Foundation\Http\FormRequest;

class PlayerSettingUpdateRequest extends FormRequest
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
        return [
            'user_id'=> ['required', 'uuid'],
            'sound_status' => ['nullable'],
            'vibration_status' => ['nullable'],
            'challenge_status' => ['nullable'],
            'friend_status' => ['nullable'],
        ];
    }
}
