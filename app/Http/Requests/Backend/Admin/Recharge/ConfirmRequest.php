<?php

namespace App\Http\Requests\Api\Admin\Recharge;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmRequest extends FormRequest
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
            'id' => ['required', 'uuid'],
            'amount' => ['required', 'numeric', 'min:1'],
            'received_amount' => ['required', 'numeric', 'integer'],
            'received_from' => ['string', 'max:255'],
            'description' => ['string', 'max:500'],
        ];
    }
}
