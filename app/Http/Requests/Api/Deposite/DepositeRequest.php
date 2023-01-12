<?php

namespace App\Http\Requests\Api\Deposite;

use Illuminate\Foundation\Http\FormRequest;

class DepositeRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'account_name'  => ['string'],
            'phone_number' => ['required', 'string'],
            'amount' => ['required', 'string'],
            'transaction_photo' =>  ['image', 'mimes:png,jpg,jpeg'],
            'agent_text' => ['required','string'],
            'agent_photo' => ['required','image', 'mimes:png,jpg,jpeg'],
        ];
    }
}
