<?php

namespace App\Http\Requests\Api\Friend;

use App\Actions\DevelopmentValidator;
use Illuminate\Foundation\Http\FormRequest;

class FriendConfirmRequest extends FormRequest
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
            'user_id' => ['required', 'uuid'],
            'friend_id' => ['required', 'uuid'],
        ]);

        return [];
    }
}
